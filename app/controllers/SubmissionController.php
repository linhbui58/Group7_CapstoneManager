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

        // Fallback: nếu chưa có approved registration, hiện tất cả topic approved
        if (empty($topics)) {
            $topicModel = new Topic();
            $topics     = $topicModel->getAvailable();
        }

        require '../app/views/submissions/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('submission-create');
        }

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

        // Upload file
        $filename  = null;
        $fileField = !empty($_FILES['report_file']['name']) ? 'report_file' : 'file';
        if (!empty($_FILES[$fileField]['name'])) {
            $uploadDir = __DIR__ . '/../../public/assets/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $filename = time() . '_' . basename($_FILES[$fileField]['name']);
            move_uploaded_file($_FILES[$fileField]['tmp_name'], $uploadDir . $filename);
        }

        $this->submissionModel->create([
            'student_id'   => $studentId,
            'topic_id'     => $topicId,
            'milestone_id' => $milestoneId,
            'file_path'    => $filename,
        ]);

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
            $notifModel->create(['user_id' => $admin['id'], 'content' => $content, 'type' => 'submission']);
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
            $notifModel->create(['user_id' => $lu['id'], 'content' => $content, 'type' => 'submission']);
        }

        $_SESSION['success'] = "Nộp bài thành công.";
        redirect('submissions');
    }

    public function updateStatus() {
        $role = $_SESSION['user']['role'];
        if (!in_array($role, ['admin', 'lecturer'])) {
            http_response_code(403);
            $_SESSION['error'] = "Bạn không có quyền thực hiện thao tác này.";
            redirect('submissions');
        }

        $id     = (int)($_GET['id']     ?? 0);
        $status = $_GET['status'] ?? '';

        // Map UI status → DB enum
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
                    $_SESSION['error'] = "Bạn không có quyền duyệt bài nộp này.";
                    redirect('submissions');
                }
            }

            $this->submissionModel->updateStatus($id, $dbStatus);

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
                    'type'    => 'submission',
                ]);
            }

            $_SESSION['success'] = "Cập nhật trạng thái thành công.";
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
                    $_SESSION['error'] = "Bạn không có quyền xóa bài nộp này.";
                    redirect('submissions');
                }
            }
            $this->submissionModel->delete($id);
            $_SESSION['success'] = "Đã xóa bài nộp.";
        }
        redirect('submissions');
    }
}
