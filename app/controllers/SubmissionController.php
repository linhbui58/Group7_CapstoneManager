<?php

class SubmissionController {

    private $submissionModel;

    public function __construct() {
        AuthMiddleware::check();
        $this->submissionModel = new Submission();
    }

    public function index() {
        $role = $_SESSION['user']['role'];

        if ($role === 'student') {
            $studentId = $_SESSION['user']['student_id'] ?? null;
            if (!$studentId) {
                $studentModel = new Student();
                $student = $studentModel->findByUserId($_SESSION['user']['id']);
                if ($student) {
                    $_SESSION['user']['student_id'] = $student['id'];
                    $studentId = $student['id'];
                }
            }
            $submissions = $studentId
                ? $this->submissionModel->getByStudent($studentId)
                : [];
        } elseif ($role === 'lecturer') {
            $lecturerId  = $_SESSION['user']['lecturer_id'] ?? null;
            $submissions = $lecturerId
                ? $this->submissionModel->getByLecturer($lecturerId)
                : [];
        } else {
            $submissions = $this->submissionModel->getAll();
        }

        require '../app/views/submissions/index.php';
    }

    public function show() {
        $id         = (int)($_GET['id'] ?? 0);
        $submission = $this->submissionModel->find($id);
        if (!$submission) {
            $_SESSION['error'] = "KhÃ´ng tÃ¬m tháº¥y bÃ i ná»™p.";
            redirect('submissions');
        }

        $role = $_SESSION['user']['role'];
        if ($role === 'student') {
            $studentId = $_SESSION['user']['student_id'] ?? null;
            if ($submission['student_id'] != $studentId) {
                $_SESSION['error'] = "Báº¡n khÃ´ng cÃ³ quyá»n xem bÃ i ná»™p nÃ y.";
                redirect('submissions');
            }
        } elseif ($role === 'lecturer') {
            $lecturerId = $_SESSION['user']['lecturer_id'] ?? null;
            if (!$this->submissionModel->belongsToLecturer($id, $lecturerId)) {
                $_SESSION['error'] = "Báº¡n khÃ´ng cÃ³ quyá»n xem bÃ i ná»™p nÃ y.";
                redirect('submissions');
            }
        }

        require '../app/views/submissions/show.php';
    }

    public function create() {
        if ($_SESSION['user']['role'] !== 'student') {
            redirect('submissions');
        }
        $milestoneModel = new Milestone();
        $milestones     = $milestoneModel->getAll();

        // Láº¥y topic cá»§a sinh viÃªn (Ä‘Ã£ approved hoáº·c táº¥t cáº£)
        $db        = Database::getInstance()->getConnection();
        $studentId = $_SESSION['user']['student_id'] ?? null;
        if (!$studentId) {
            $studentModel = new Student();
            $student = $studentModel->findByUserId($_SESSION['user']['id']);
            if ($student) {
                $_SESSION['user']['student_id'] = $student['id'];
                $studentId = $student['id'];
            }
        }

        // Láº¥y topic sinh viÃªn Ä‘Ã£ Ä‘Äƒng kÃ½ (approved) hoáº·c táº¥t cáº£ topic available
        if ($studentId) {
            $stmt = $db->prepare(
                "SELECT t.id, t.title FROM topics t
                 JOIN topic_registrations tr ON tr.topic_id = t.id
                 WHERE tr.student_id = ? AND tr.status = 'approved'
                 ORDER BY t.id DESC"
            );
            $stmt->execute([$studentId]);
            $topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Xóa đoạn Fallback tải toàn bộ đề tài. Sinh viên chỉ được nộp bài cho đề tài đã duyệt của mình.

        require '../app/views/submissions/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            verifyCSRF();
            redirect('submission-create');
        }
        verifyCSRF();

        $studentId = $_SESSION['user']['student_id'] ?? null;
        if (!$studentId) {
            $studentModel = new Student();
            $student = $studentModel->findByUserId($_SESSION['user']['id']);
            if ($student) {
                $_SESSION['user']['student_id'] = $student['id'];
                $studentId = $student['id'];
            }
        }

        if (!$studentId) {
            $_SESSION['error'] = "KhÃ´ng xÃ¡c Ä‘á»‹nh Ä‘Æ°á»£c sinh viÃªn.";
            redirect('submission-create');
        }

        $milestoneId = (int)($_POST['milestone_id'] ?? 0);
        $topicId     = (int)($_POST['topic_id']     ?? 0) ?: null;

        if (!$milestoneId) {
            $_SESSION['error'] = "Vui lòng chọn cột mốc.";
            redirect('submission-create');
        }

        $milestoneModel = new Milestone();
        $milestone = $milestoneModel->find($milestoneId);
        if ($milestone && time() > strtotime($milestone['deadline'])) {
            $_SESSION['error'] = "Đã quá hạn nộp bài cho cột mốc này.";
            redirect('submission-create');
        }

        // Upload file
        $filename  = null;
        $fileField = !empty($_FILES['report_file']['name']) ? 'report_file' : 'file';
        if (!empty($_FILES[$fileField]['name'])) {
            require_once '../app/helpers/upload.php';
            $uploadResult = uploadFile($_FILES[$fileField], 'submissions');
            if (!$uploadResult['status']) {
                $_SESSION['error'] = $uploadResult['message'];
                redirect('submission-create');
                return;
            }
            $filename = basename($uploadResult['path']);
        } else {
            $_SESSION['error'] = "Vui lÃ²ng chá»n file ná»™p bÃ i.";
            redirect('submission-create');
            return;
        }

        $db = Database::getInstance()->getConnection();
        $stmtAttempt = $db->prepare("SELECT MAX(attempt) FROM submissions WHERE student_id = ? AND topic_id <=> ? AND milestone_id = ?");
        $stmtAttempt->execute([$studentId, $topicId, $milestoneId]);
        $maxAttempt = (int)$stmtAttempt->fetchColumn();
        $attempt = $maxAttempt + 1;

        $this->submissionModel->create([
            'student_id'   => $studentId,
            'topic_id'     => $topicId,
            'milestone_id' => $milestoneId,
            'file_path'    => $filename,
            'attempt'      => $attempt,
        ]);

        LogService::log('create_submission', "Student ID: $studentId submitted for Milestone ID: $milestoneId");

        // Gá»­i notification cho admin vÃ  lecturer phá»¥ trÃ¡ch
        $notifModel = new Notification();
        $db = Database::getInstance()->getConnection();

        $stmtS = $db->prepare("SELECT full_name FROM students WHERE id = ?");
        $stmtS->execute([$studentId]);
        $studentName = $stmtS->fetchColumn() ?: 'Sinh viÃªn';

        $stmtM = $db->prepare("SELECT title FROM milestones WHERE id = ?");
        $stmtM->execute([$milestoneId]);
        $milestoneTitle = $stmtM->fetchColumn() ?: 'milestone';

        $content = "$studentName Ä‘Ã£ ná»™p bÃ i cho cá»™t má»‘c: $milestoneTitle";

        // Gá»­i cho admin
        $admins = $db->query("SELECT id FROM users WHERE role = 'admin'")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($admins as $admin) {
            $notifModel->create(['user_id' => $admin['id'], 'content' => $content, 'type' => 'submission']);
        }

        // Gá»­i cho lecturer phá»¥ trÃ¡ch (qua topic_assignments hoáº·c desired_lecturer_id)
        if ($topicId) {
            $stmtL = $db->prepare(
                "SELECT u.id FROM topic_assignments ta
                 JOIN lecturers l ON l.id = ta.lecturer_id
                 JOIN users u ON u.id = l.user_id
                 WHERE ta.topic_id = ?"
            );
            $stmtL->execute([$topicId]);
        } else {
            $stmtL = $db->prepare(
                "SELECT u.id FROM topic_registrations tr
                 JOIN lecturers l ON l.id = tr.desired_lecturer_id
                 JOIN users u ON u.id = l.user_id
                 WHERE tr.student_id = ? AND tr.desired_lecturer_id IS NOT NULL
                 LIMIT 1"
            );
            $stmtL->execute([$studentId]);
        }
        $lecturerUsers = $stmtL->fetchAll(PDO::FETCH_ASSOC);
        foreach ($lecturerUsers as $lu) {
            $notifModel->create(['user_id' => $lu['id'], 'content' => $content, 'type' => 'submission']);
        }

        $_SESSION['success'] = "Ná»™p bÃ i thÃ nh cÃ´ng.";
        redirect('submissions');
    }

    public function updateStatus() {
        $role = $_SESSION['user']['role'];
        if (!in_array($role, ['admin', 'lecturer'])) {
            http_response_code(403);
            $_SESSION['error'] = "Báº¡n khÃ´ng cÃ³ quyá»n thá»±c hiá»‡n thao tÃ¡c nÃ y.";
            redirect('submissions');
        }

        $id     = (int)($_GET['id']     ?? 0);
        $status = $_GET['status'] ?? '';

        // Map UI status â†’ DB enum
        $validStatuses = ['submitted', 'late', 'revision_required'];
        $statusMap = [
            'reviewed'           => 'submitted',
            'rejected'           => 'revision_required',
            'submitted'          => 'submitted',
            'late'               => 'late',
            'revision_required'  => 'revision_required',
        ];

        if (!isset($statusMap[$status])) {
            redirect('submissions');
        }
        $dbStatus = $statusMap[$status];

        if ($id) {
            if ($role === 'lecturer') {
                $lecturerId = $_SESSION['user']['lecturer_id'] ?? null;
                if (!$lecturerId || !$this->submissionModel->belongsToLecturer($id, $lecturerId)) {
                    $_SESSION['error'] = "Báº¡n khÃ´ng cÃ³ quyá»n duyá»‡t bÃ i ná»™p nÃ y.";
                    redirect('submissions');
                }
            }

            $this->submissionModel->updateStatus($id, $dbStatus);
            LogService::log('update_submission_status', "Updated submission ID: $id status to $dbStatus");

            // Notification cho sinh viÃªn
            $db   = Database::getInstance()->getConnection();
            $stmt = $db->prepare(
                "SELECT sub.student_id, s.user_id, m.title AS milestone_title
                 FROM submissions sub
                 JOIN students  s ON s.id  = sub.student_id
                 JOIN milestones m ON m.id = sub.milestone_id
                 WHERE sub.id = ?"
            );
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $label = match($dbStatus) {
                    'submitted'         => 'Ä‘Ã£ Ä‘Æ°á»£c xÃ¡c nháº­n âœ“',
                    'revision_required' => 'cáº§n chá»‰nh sá»­a láº¡i âœï¸',
                    'late'              => 'bá»‹ Ä‘Ã¡nh dáº¥u ná»™p trá»… âš ï¸',
                    default             => 'Ä‘Ã£ Ä‘Æ°á»£c cáº­p nháº­t',
                };
                $notifModel = new Notification();
                $notifModel->create([
                    'user_id' => $row['user_id'],
                    'content' => "BÃ i ná»™p cá»™t má»‘c \"{$row['milestone_title']}\" cá»§a báº¡n $label.",
                    'type'    => 'submission',
                ]);
            }

            $_SESSION['success'] = "Cáº­p nháº­t tráº¡ng thÃ¡i thÃ nh cÃ´ng.";
        }

        redirect('submissions');
    }

    public function delete() {
        $role = $_SESSION['user']['role'];
        if (!in_array($role, ['admin', 'lecturer'])) {
            redirect('submissions');
        }

        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            if ($role === 'lecturer') {
                $lecturerId = $_SESSION['user']['lecturer_id'] ?? null;
                if (!$lecturerId || !$this->submissionModel->belongsToLecturer($id, $lecturerId)) {
                    $_SESSION['error'] = "Báº¡n khÃ´ng cÃ³ quyá»n xÃ³a bÃ i ná»™p nÃ y.";
                    redirect('submissions');
                }
            }
            $this->submissionModel->delete($id);
            LogService::log('delete_submission', "Deleted submission ID: $id");
            $_SESSION['success'] = "ÄÃ£ xÃ³a bÃ i ná»™p.";
        }
        redirect('submissions');
    }
}
