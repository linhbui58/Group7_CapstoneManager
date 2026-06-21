<?php

class DashboardController {

    public function index() {
        AuthMiddleware::check();
        $role = userRole();

        if ($role === 'admin') {
            $this->adminDashboard();
        } elseif ($role === 'lecturer') {
            $this->lecturerDashboard();
        } elseif ($role === 'student') {
            $this->studentDashboard();
        } else {
            abort(403);
        }
    }

    private function adminDashboard() {
        $topicModel      = new Topic();
        $studentModel    = new Student();
        $lecturerModel   = new Lecturer();
        $submissionModel = new Submission();
        $db = Database::getInstance()->getConnection();

        $allTopics        = $topicModel->getAll();
        $allSubmissions   = $submissionModel->getAll();

        $totalTopics      = count($allTopics);
        $totalStudents    = count($studentModel->getAll());
        $totalLecturers   = count($lecturerModel->getAll());
        $totalSubmissions = count($allSubmissions);

        $draftCount    = 0;
        $pendingCount  = 0;
        $approvedCount = 0;
        $rejectedCount = 0;
        foreach ($allTopics as $t) {
            switch ($t['status'] ?? '') {
                case 'draft':    $draftCount++;    break;
                case 'pending':  $pendingCount++;  break;
                case 'approved': $approvedCount++; break;
                case 'rejected': $rejectedCount++; break;
            }
        }

        $recentTopics      = array_slice($allTopics, 0, 5);
        $recentSubmissions = array_slice($allSubmissions, 0, 5);

        // Submissions by month (last 6 months)
        $stmt = $db->query("
            SELECT DATE_FORMAT(submitted_at,'%Y-%m') AS ym, COUNT(*) AS cnt
            FROM submissions
            WHERE submitted_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY ym ORDER BY ym ASC
        ");
        $submissionsByMonth = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];

        // Topics created by month (last 6 months)
        $stmt2 = $db->query("
            SELECT DATE_FORMAT(created_at,'%Y-%m') AS ym, COUNT(*) AS cnt
            FROM topics
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY ym ORDER BY ym ASC
        ");
        $topicsByMonth = $stmt2 ? $stmt2->fetchAll(PDO::FETCH_ASSOC) : [];

        require '../app/views/dashboard/admin_dashboard.php';
    }

    private function lecturerDashboard() {
        $userId = userId();
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM lecturers WHERE user_id = ?");
        $stmt->execute([$userId]);
        $lecturer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$lecturer) {
            // Tự tạo profile lecturer nếu chưa có
            $db->prepare("INSERT INTO lecturers (user_id, full_name, expertise, quota) VALUES (?, ?, '', 8)")
               ->execute([$userId, explode('@', $_SESSION['user']['email'])[0]]);
            $stmt = $db->prepare("SELECT * FROM lecturers WHERE user_id = ?");
            $stmt->execute([$userId]);
            $lecturer = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        $lecturerId = $lecturer['id'];
        $workload = WorkloadService::getLecturerWorkload($lecturerId);
        $quota = $lecturer['quota'];

        // Get assigned topics
        $stmt = $db->prepare("
            SELECT t.* 
            FROM topics t
            JOIN topic_assignments ta ON t.id = ta.topic_id
            WHERE ta.lecturer_id = ?
        ");
        $stmt->execute([$lecturerId]);
        $assignedTopics = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $totalAssignedTopics = count($assignedTopics);

        // Submissions to review
        $stmt = $db->prepare("
            SELECT s.*, t.title as topic_title, st.full_name as student_name
            FROM submissions s
            JOIN topics t ON s.topic_id = t.id
            JOIN topic_assignments ta ON t.id = ta.topic_id
            JOIN students st ON s.student_id = st.id
            LEFT JOIN evaluation_scores es ON s.id = es.submission_id
            WHERE ta.lecturer_id = ? AND es.id IS NULL
        ");
        $stmt->execute([$lecturerId]);
        $pendingReviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $totalPendingReviews = count($pendingReviews);

        // Scores given by this lecturer by month (last 6 months)
        $db2 = Database::getInstance()->getConnection();
        $stmtSc = $db2->prepare("
            SELECT DATE_FORMAT(es.graded_at,'%Y-%m') AS ym,
                   ROUND(AVG(es.score),1) AS avg_score,
                   COUNT(*) AS cnt
            FROM evaluation_scores es
            JOIN submissions s ON es.submission_id = s.id
            JOIN topics t ON s.topic_id = t.id
            JOIN topic_assignments ta ON t.id = ta.topic_id
            WHERE ta.lecturer_id = ?
              AND es.graded_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY ym ORDER BY ym ASC
        ");
        $stmtSc->execute([$lecturerId]);
        $scoresByMonth = $stmtSc->fetchAll(PDO::FETCH_ASSOC);

        // Sinh viên đang được hướng dẫn
        $stmtSv = $db->prepare("
            SELECT s.full_name, s.faculty, sa.assigned_at, sem.name AS semester_name,
                   t.title AS topic_title
            FROM supervision_assignments sa
            JOIN students s   ON s.id   = sa.student_id
            JOIN semesters sem ON sem.id = sa.semester_id
            LEFT JOIN topic_registrations tr ON tr.student_id = s.id AND tr.status IN ('approved','registered')
            LEFT JOIN topics t ON t.id = tr.topic_id
            WHERE sa.lecturer_id = ?
            ORDER BY sa.assigned_at DESC
        ");
        $stmtSv->execute([$lecturerId]);
        $supervisedStudents = $stmtSv->fetchAll(PDO::FETCH_ASSOC);

        require '../app/views/dashboard/lecturer_dashboard.php';
    }

    private function studentDashboard() {
        $userId = userId();
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM students WHERE user_id = ?");
        $stmt->execute([$userId]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$student) {
            // Tự tạo profile student nếu chưa có
            $db->prepare("INSERT INTO students (user_id, full_name) VALUES (?, ?)")
               ->execute([$userId, explode('@', $_SESSION['user']['email'])[0]]);
            $stmt = $db->prepare("SELECT * FROM students WHERE user_id = ?");
            $stmt->execute([$userId]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        $studentId = $student['id'];
        $progress = ProgressService::getStudentProgress($studentId);

        // Get Registered Topic
        $stmt = $db->prepare("
            SELECT t.title, t.status 
            FROM topics t
            JOIN topic_registrations tr ON t.id = tr.topic_id
            WHERE tr.student_id = ? AND tr.status = 'approved'
            LIMIT 1
        ");
        $stmt->execute([$studentId]);
        $registeredTopic = $stmt->fetch(PDO::FETCH_ASSOC);

        // Upcoming Milestones
        $stmt = $db->prepare("
            SELECT * FROM milestones 
            WHERE deadline > NOW() 
            ORDER BY deadline ASC LIMIT 3
        ");
        $stmt->execute();
        $upcomingMilestones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Submissions by month for this student (last 6 months)
        $stmt = $db->prepare("
            SELECT DATE_FORMAT(submitted_at,'%Y-%m') AS ym, COUNT(*) AS cnt
            FROM submissions
            WHERE student_id = ? AND submitted_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY ym ORDER BY ym ASC
        ");
        $stmt->execute([$studentId]);
        $submissionsByMonth = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Topic status counts for donut chart
        $topicStatusData = [
            'draft'    => 0,
            'pending'  => 0,
            'approved' => 0,
            'rejected' => 0,
        ];
        $stmtTs = $db->prepare("
            SELECT t.status, COUNT(*) as cnt
            FROM topics t
            JOIN topic_registrations tr ON t.id = tr.topic_id
            WHERE tr.student_id = ?
            GROUP BY t.status
        ");
        $stmtTs->execute([$studentId]);
        foreach ($stmtTs->fetchAll(PDO::FETCH_ASSOC) as $row) {
            if (isset($topicStatusData[$row['status']])) {
                $topicStatusData[$row['status']] = (int)$row['cnt'];
            }
        }

        $this->sendDeadlineReminders($userId, $upcomingMilestones);

        // My Scores
        $stmtScores = $db->prepare("
            SELECT m.title AS milestone, es.score, es.feedback, es.graded_at,
                   t.title AS topic_title
            FROM evaluation_scores es
            JOIN submissions s ON es.submission_id = s.id
            JOIN milestones m  ON s.milestone_id   = m.id
            JOIN topics t      ON s.topic_id        = t.id
            WHERE s.student_id = ?
            ORDER BY es.graded_at DESC
        ");
        $stmtScores->execute([$studentId]);
        $myScores = $stmtScores->fetchAll(PDO::FETCH_ASSOC);

        require '../app/views/dashboard/student_dashboard.php';
    }

    private function sendDeadlineReminders($userId, $upcomingMilestones) {
        $db = Database::getInstance()->getConnection();
        $notifModel = new Notification();
        $now = time();

        foreach ($upcomingMilestones as $m) {
            $deadlineTime = strtotime($m['deadline']);
            $timeDiff = $deadlineTime - $now;

            // Nếu deadline < 24h và chưa quá hạn
            if ($timeDiff > 0 && $timeDiff <= 24 * 3600) {
                $formattedDeadline = date('H:i d/m/Y', $deadlineTime);
                $content = "⏰ Reminder: Milestone \"{$m['title']}\" is due at $formattedDeadline";

                // Check xem hôm nay đã gửi nhắc nhở này chưa
                $stmt = $db->prepare("
                    SELECT COUNT(*) FROM notifications 
                    WHERE user_id = ? AND content = ? AND DATE(created_at) = CURDATE()
                ");
                $stmt->execute([$userId, $content]);
                $alreadySent = $stmt->fetchColumn();

                if (!$alreadySent) {
                    $notifModel->create([
                        'user_id' => $userId,
                        'content' => $content,
                        'type'    => 'deadline'
                    ]);
                }
            }
        }
    }
}
