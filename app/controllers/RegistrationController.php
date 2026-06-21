<?php
class RegistrationController {

    private $regModel;

    public function __construct() {
        AuthMiddleware::check();
        $this->regModel = new TopicRegistration();
    }

    /* ──────────────────────────────────────────────────────────
     | CREATE FORM  (student only)
     ────────────────────────────────────────────────────────── */
    public function create() {
        if ($_SESSION['user']['role'] !== 'student') {
            header("Location: index.php?page=registrations");
            exit();
        }

        $topicModel    = new Topic();
        $semesterModel = new Semester();

        $topics    = $topicModel->getAvailable($_SESSION['user']['id']);   // chỉ lấy đề tài của user này đã được duyệt
        $semesters = $semesterModel->getAll();

        require '../app/views/registrations/create.php';
    }

    /* ──────────────────────────────────────────────────────────
     | STORE  (student only)
     ────────────────────────────────────────────────────────── */
    public function store() {
        if ($_SESSION['user']['role'] !== 'student') {
            header("Location: index.php?page=registrations");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=registration-create");
            exit();
        }
        verifyCSRF();

        $studentId  = $_SESSION['user']['student_id'] ?? null;

        // Fallback: lấy student_id từ DB nếu session chưa có
        if (!$studentId) {
            $studentModel = new Student();
            $student = $studentModel->findByUserId($_SESSION['user']['id']);
            if ($student) {
                $_SESSION['user']['student_id'] = $student['id'];
                $studentId = $student['id'];
            }
        }

        $topicId    = (int)($_POST['topic_id']    ?? 0);
        $semesterId = (int)($_POST['semester_id'] ?? 0);
        $keywords   = trim($_POST['keywords'] ?? '');

        if (!$studentId || !$topicId || !$semesterId) {
            $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin.";
            header("Location: index.php?page=registration-create");
            exit();
        }

        // Kiểm tra deadline
        $semesterModel = new Semester();
        $semester = $semesterModel->find($semesterId);
        if ($semester && time() > strtotime($semester['end_date'] . ' 23:59:59')) {
            $_SESSION['error'] = "Đã hết hạn đề xuất đề tài cho học kỳ này.";
            header("Location: index.php?page=registration-create");
            exit();
        }

        // Đã registered thì không được đề xuất thêm
        if ($this->regModel->hasRegistered($studentId, $semesterId)) {
            $_SESSION['error'] = "Bạn đã đăng ký chính thức 1 đề tài trong học kỳ này, không thể đề xuất thêm.";
            header("Location: index.php?page=registration-create");
            exit();
        }

        // Kiểm tra xem đã được phân công GVHD chưa
        require_once '../app/models/SupervisionAssignment.php';
        $supervisionModel = new SupervisionAssignment();
        $assignment = $supervisionModel->findByStudentAndSemester($studentId, $semesterId);
        
        if (!$assignment) {
            $_SESSION['error'] = "Bạn cần được phân công giảng viên hướng dẫn trước khi đề xuất đề tài trong học kỳ này.";
            header("Location: index.php?page=registration-create");
            exit();
        }

        $desiredLec = $assignment['lecturer_id'];

        $this->regModel->create([
            'student_id'          => $studentId,
            'topic_id'            => $topicId,
            'semester_id'         => $semesterId,
            'desired_lecturer_id' => $desiredLec,
            'keywords'            => $keywords,
            'status'              => 'pending',
        ]);

        LogService::log('propose_topic', "Student ID: $studentId proposed Topic ID: $topicId");

        $_SESSION['success'] = "Đề xuất đề tài thành công. Vui lòng chờ duyệt.";
        header("Location: index.php?page=topic-management&tab=registrations");
        exit();
    }

    /* ──────────────────────────────────────────────────────────
     | EDIT FORM (student only, pending status)
     ────────────────────────────────────────────────────────── */
    public function edit() {
        if ($_SESSION['user']['role'] !== 'student') {
            header("Location: index.php?page=registrations");
            exit();
        }

        $id = (int)($_GET['id'] ?? 0);
        $reg = $this->regModel->find($id);

        $studentId = $_SESSION['user']['student_id'] ?? null;
        if (!$reg || $reg['student_id'] != $studentId) {
            $_SESSION['error'] = "Không tìm thấy đề xuất.";
            header("Location: index.php?page=topic-management&tab=registrations");
            exit();
        }

        if ($reg['status'] !== 'pending') {
            $_SESSION['error'] = "Chỉ có thể sửa đề xuất đang chờ duyệt.";
            header("Location: index.php?page=topic-management&tab=registrations");
            exit();
        }

        $topicModel = new Topic();
        $topics = $topicModel->getAvailable($_SESSION['user']['id']);

        require '../app/views/registrations/edit.php';
    }

    /* ──────────────────────────────────────────────────────────
     | UPDATE (student only, pending status)
     ────────────────────────────────────────────────────────── */
    public function update() {
        if ($_SESSION['user']['role'] !== 'student' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=registrations");
            exit();
        }
        verifyCSRF();

        $id = (int)($_POST['id'] ?? 0);
        $topicId = (int)($_POST['topic_id'] ?? 0);
        $keywords = trim($_POST['keywords'] ?? '');

        $reg = $this->regModel->find($id);
        $studentId = $_SESSION['user']['student_id'] ?? null;

        if (!$reg || $reg['student_id'] != $studentId) {
            $_SESSION['error'] = "Không tìm thấy đề xuất.";
            header("Location: index.php?page=topic-management&tab=registrations");
            exit();
        }

        if ($reg['status'] !== 'pending') {
            $_SESSION['error'] = "Chỉ có thể sửa đề xuất đang chờ duyệt.";
            header("Location: index.php?page=topic-management&tab=registrations");
            exit();
        }

        $this->regModel->updateContent($id, $topicId, $keywords);
        $_SESSION['success'] = "Cập nhật đề xuất thành công.";
        header("Location: index.php?page=topic-management&tab=registrations");
        exit();
    }

    /* ──────────────────────────────────────────────────────────
     | DELETE (student only, pending status)
     ────────────────────────────────────────────────────────── */
    public function delete() {
        if ($_SESSION['user']['role'] !== 'student' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=registrations");
            exit();
        }
        verifyCSRF();

        $id = (int)($_GET['id'] ?? 0);
        $reg = $this->regModel->find($id);
        $studentId = $_SESSION['user']['student_id'] ?? null;

        if (!$reg || $reg['student_id'] != $studentId) {
            $_SESSION['error'] = "Không tìm thấy đề xuất.";
            header("Location: index.php?page=topic-management&tab=registrations");
            exit();
        }

        if ($reg['status'] !== 'pending') {
            $_SESSION['error'] = "Chỉ có thể xóa đề xuất đang chờ duyệt.";
            header("Location: index.php?page=topic-management&tab=registrations");
            exit();
        }

        $this->regModel->delete($id);
        $_SESSION['success'] = "Xóa đề xuất thành công.";
        header("Location: index.php?page=topic-management&tab=registrations");
        exit();
    }

    /* ──────────────────────────────────────────────────────────
     | REGISTER (student only, approved -> registered)
     ────────────────────────────────────────────────────────── */
    public function register() {
        if ($_SESSION['user']['role'] !== 'student' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=registrations");
            exit();
        }
        verifyCSRF();

        $id = (int)($_GET['id'] ?? 0);
        $reg = $this->regModel->find($id);
        $studentId = $_SESSION['user']['student_id'] ?? null;

        if (!$reg || $reg['student_id'] != $studentId) {
            $_SESSION['error'] = "Không tìm thấy đề xuất.";
            header("Location: index.php?page=topic-management&tab=registrations");
            exit();
        }

        if ($reg['status'] !== 'approved') {
            $_SESSION['error'] = "Chỉ có thể đăng ký đề xuất đã được duyệt.";
            header("Location: index.php?page=topic-management&tab=registrations");
            exit();
        }

        if ($this->regModel->hasRegistered($studentId, $reg['semester_id'])) {
            $_SESSION['error'] = "Bạn đã có đề tài được đăng ký chính thức trong học kỳ này.";
            header("Location: index.php?page=topic-management&tab=registrations");
            exit();
        }

        $this->regModel->updateStatus($id, 'registered');
        LogService::log('confirm_registration', "Student ID: $studentId confirmed registration for Topic ID: " . $reg['topic_id']);

        $_SESSION['success'] = "Đăng ký đề tài chính thức thành công.";
        header("Location: index.php?page=topic-management&tab=registrations");
        exit();
    }

    /* ──────────────────────────────────────────────────────────
     | UPDATE STATUS  (admin / lecturer only)
     ────────────────────────────────────────────────────────── */
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit("Method Not Allowed");
        }
        verifyCSRF();
        $role = $_SESSION['user']['role'];

        if (!in_array($role, ['admin', 'lecturer'])) {
            // Student cố tình gọi URL này -> chặn cứng
            http_response_code(403);
            $_SESSION['error'] = "Bạn không có quyền thực hiện thao tác này.";
            header("Location: index.php?page=topic-management&tab=registrations");
            exit();
        }

        $id     = (int)($_GET['id']     ?? 0);
        $status = $_GET['status'] ?? '';

        // Whitelist status
        if (!in_array($status, ['approved', 'rejected', 'pending'])) {
            header("Location: index.php?page=topic-management&tab=registrations");
            exit();
        }

        if ($id) {
            // Lecturer chỉ được duyệt đăng ký chọn mình HOẶC thuộc topic được assign cho mình
            if ($role === 'lecturer') {
                $lecturerId = $_SESSION['user']['lecturer_id'] ?? null;
                $reg = $this->regModel->find($id);
                if (!$reg) {
                    $_SESSION['error'] = "Không tìm thấy đăng ký.";
                    header("Location: index.php?page=topic-management&tab=registrations");
                    exit();
                }
                $isDesired  = (int)($reg['desired_lecturer_id'] ?? 0) === (int)$lecturerId;
                // Kiểm tra topic có được assign cho lecturer không
                $db   = Database::getInstance()->getConnection();
                $stmt = $db->prepare(
                    "SELECT COUNT(*) FROM topic_assignments WHERE topic_id = ? AND lecturer_id = ?"
                );
                $stmt->execute([$reg['topic_id'], $lecturerId]);
                $isAssigned = (int)$stmt->fetchColumn() > 0;

                if (!$isDesired && !$isAssigned) {
                    $_SESSION['error'] = "Bạn không có quyền duyệt đăng ký này.";
                    header("Location: index.php?page=topic-management&tab=registrations");
                    exit();
                }
            }
            $this->regModel->updateStatus($id, $status);

            $reg = $this->regModel->find($id);
            if ($reg) {
                require_once '../app/models/Topic.php';
                $topicModel = new Topic();
                $topic = $topicModel->find($reg['topic_id']);
                $topicTitle = $topic ? $topic['title'] : 'Đề tài không xác định';

                require_once '../app/models/Student.php';
                $studentModel = new Student();
                $student = $studentModel->find($reg['student_id']);
                
                if ($student && !empty($student['user_id'])) {
                    $statusText = $status === 'approved' ? 'đã được DUYỆT ✅' : ($status === 'rejected' ? 'đã bị TỪ CHỐI ❌' : 'được chuyển về CHỜ DUYỆT 🕐');
                    require_once '../app/models/Notification.php';
                    $notifModel = new Notification();
                    $notifModel->create([
                        'user_id' => $student['user_id'],
                        'content' => "Đề xuất đề tài \"{$topicTitle}\" của bạn $statusText.",
                        'type'    => 'approval'
                    ]);
                }
            }

            LogService::log('update_registration_status', "Updated registration ID: $id status to $status");
            $_SESSION['success'] = "Cập nhật trạng thái thành công.";
        }

        header("Location: index.php?page=topic-management&tab=registrations");
        exit();
    }
}
