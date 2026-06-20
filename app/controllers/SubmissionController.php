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
            $_SESSION['error'] = "Không tìm thấy bài nộp.";
            redirect('submissions');
        }

        $role = $_SESSION['user']['role'];
        if ($role === 'student') {
            $studentId = $_SESSION['user']['student_id'] ?? null;
            if ($submission['student_id'] != $studentId) {
                $_SESSION['error'] = "Bạn không có quyền xem bài nộp này.";
                redirect('submissions');
            }
        } elseif ($role === 'lecturer') {
            $lecturerId = $_SESSION['user']['lecturer_id'] ?? null;
            if (!$this->submissionModel->belongsToLecturer($id, $lecturerId)) {
                $_SESSION['error'] = "Bạn không có quyền xem bài nộp này.";
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

        // Lấy topic của sinh viên (đã approved hoặc tất cả)
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

        // Lấy topic sinh viên đã đăng ký (approved) hoặc tất cả topic available
        $topics = [];
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
            $_SESSION['error'] = "Không xác định được sinh viên.";
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
        $fileField = (!empty($_FILES['report_file']['name']) && isset($_FILES['report_file'])) ? 'report_file' : (isset($_FILES['file']) ? 'file' : null);
        if ($fileField && !empty($_FILES[$fileField]['name'])) {
            require_once '../app/helpers/upload.php';
            $uploadResult = uploadFile($_FILES[$fileField], 'submissions');
            if (!$uploadResult['status']) {
                $_SESSION['error'] = $uploadResult['message'];
                redirect('submission-create');
                return;
            }
            $filename = basename($uploadResult['path']);
        } else {
            $_SESSION['error'] = "Vui lòng chọn file nộp bài.";
            redirect('submission-create');
            return;
        }

        $db = Database::getInstance()->getConnection();
        $stmtAttempt = $db->prepare("SELECT MAX(attempt) FROM submissions WHERE student_id = ? AND (topic_id = ? OR (topic_id IS NULL AND ? IS NULL)) AND milestone_id = ?");
        $stmtAttempt->execute([$studentId, $topicId, $topicId, $milestoneId]);
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

        // Gửi notification cho admin và lecturer phụ trách
        $notifModel = new Notification();
        $db = Database::getInstance()->getConnection();

        $stmtS = $db->prepare("SELECT full_name FROM students WHERE id = ?");
        $stmtS->execute([$studentId]);
        $studentName = $stmtS->fetchColumn() ?: 'Sinh viên';

        $stmtM = $db->prepare("SELECT title FROM milestones WHERE id = ?");
        $stmtM->execute([$milestoneId]);
        $milestoneTitle = $stmtM->fetchColumn() ?: 'milestone';

        $content = "$studentName đã nộp bài cho cột mốc: $milestoneTitle";

        // Gửi cho admin
        $admins = $db->query("SELECT id FROM users WHERE role = 'admin'")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($admins as $admin) {
            $notifModel->create(['user_id' => $admin['id'], 'content' => $content, 'type' => 'system']);
        }

        // Gửi cho lecturer phụ trách (qua topic_assignments hoặc desired_lecturer_id)
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
            $notifModel->create(['user_id' => $lu['id'], 'content' => $content, 'type' => 'system']);
        }

        $_SESSION['success'] = "Nộp bài thành công.";
        redirect('submissions');
    }

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit("Method Not Allowed");
        }
        verifyCSRF();
        $role = $_SESSION['user']['role'];
        if (!in_array($role, ['admin', 'lecturer'])) {
            http_response_code(403);
            $_SESSION['error'] = "Bạn không có quyền thực hiện thao tác này.";
            redirect('submissions');
        }

        $id     = (int)($_GET['id']     ?? 0);
        $status = $_GET['status'] ?? '';

        // Map UI status → DB enum
        $validStatuses = ['submitted', 'late', 'revision_required', 'reviewed'];
        $statusMap = [
            'reviewed'           => 'reviewed',
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
                    $_SESSION['error'] = "Bạn không có quyền duyệt bài nộp này.";
                    redirect('submissions');
                }
            }

            $this->submissionModel->updateStatus($id, $dbStatus);
            LogService::log('update_submission_status', "Updated submission ID: $id status to $dbStatus");

            // Notification cho sinh viên
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
                    'submitted'         => 'đã được xác nhận ✓',
                    'revision_required' => 'cần chỉnh sửa lại ✏️',
                    'late'              => 'bị đánh dấu nộp trễ ⚠️',
                    default             => 'đã được cập nhật',
                };
                $notifModel = new Notification();
                $notifModel->create([
                    'user_id' => $row['user_id'],
                    'content' => "Bài nộp cột mốc \"{$row['milestone_title']}\" của bạn $label.",
                    'type'    => 'approval',
                ]);
            }

            $_SESSION['success'] = "Cập nhật trạng thái thành công.";
        }

        redirect('submissions');
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit("Method Not Allowed");
        }
        verifyCSRF();
        $role = $_SESSION['user']['role'];
        if (!in_array($role, ['admin', 'lecturer'])) {
            redirect('submissions');
        }

        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            if ($role === 'lecturer') {
                $lecturerId = $_SESSION['user']['lecturer_id'] ?? null;
                if (!$lecturerId || !$this->submissionModel->belongsToLecturer($id, $lecturerId)) {
                    $_SESSION['error'] = "Bạn không có quyền xóa bài nộp này.";
                    redirect('submissions');
                }
            }
            $this->submissionModel->delete($id);
            LogService::log('delete_submission', "Deleted submission ID: $id");
            $_SESSION['success'] = "Đã xóa bài nộp.";
        }
        redirect('submissions');
    }
}
