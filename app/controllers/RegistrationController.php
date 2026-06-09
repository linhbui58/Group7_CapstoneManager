<?php
class RegistrationController {

    private $regModel;

    public function __construct() {
        AuthMiddleware::check();
        $this->regModel = new TopicRegistration();
    }

    /* ──────────────────────────────────────────────
     | LIST
     | Admin / Lecturer : xem tất cả
     | Student          : chỉ xem của mình
     ────────────────────────────────────────────── */
    public function index() {
        $role = $_SESSION['user']['role'] ?? '';

        if ($role === 'admin') {
            $registrations = $this->regModel->getAll();
        } elseif ($role === 'lecturer') {
            // Lecturer chỉ thấy đăng ký của sinh viên chọn mình
            $lecturerId = $_SESSION['user']['lecturer_id'] ?? null;

            // Nếu session chưa có lecturer_id, thử lấy lại từ DB
            if (!$lecturerId) {
                $lecturerModel = new Lecturer();
                $lecturer = $lecturerModel->findByUserId($_SESSION['user']['id']);
                if ($lecturer) {
                    $_SESSION['user']['lecturer_id'] = $lecturer['id'];
                    $lecturerId = $lecturer['id'];
                }
            }

            $registrations = $lecturerId
                ? $this->regModel->getByLecturer($lecturerId)
                : [];
        } elseif ($role === 'student') {
            $studentId = $_SESSION['user']['student_id'] ?? null;

            // Nếu session chưa có student_id, thử lấy lại từ DB
            if (!$studentId) {
                $studentModel = new Student();
                $student = $studentModel->findByUserId($_SESSION['user']['id']);
                if ($student) {
                    $_SESSION['user']['student_id'] = $student['id'];
                    $studentId = $student['id'];
                }
            }

            $registrations = $studentId
                ? $this->regModel->getByStudent($studentId)
                : [];
        } else {
            $registrations = [];
        }

        require '../app/views/registrations/index.php';
    }

    /* ──────────────────────────────────────────────
     | CREATE FORM  (student only)
     ────────────────────────────────────────────── */
    public function create() {
        if ($_SESSION['user']['role'] !== 'student') {
            header("Location: index.php?page=registrations");
            exit();
        }

        $topicModel    = new Topic();
        $semesterModel = new Semester();
        $lecturerModel = new Lecturer();

        $topics    = $topicModel->getAvailable();   // chỉ topic đang mở
        $semesters = $semesterModel->getAll();
        $lecturers = $lecturerModel->getAll();

        require '../app/views/registrations/create.php';
    }

    /* ──────────────────────────────────────────────
     | STORE  (student only)
     | Constraint: không đăng ký > 1 đề tài / học kỳ
     ────────────────────────────────────────────── */
    public function store() {
        if ($_SESSION['user']['role'] !== 'student') {
            header("Location: index.php?page=registrations");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=registration-create");
            exit();
        }

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
        $desiredLec = $_POST['desired_lecturer_id'] ? (int)$_POST['desired_lecturer_id'] : null;
        $keywords   = trim($_POST['keywords'] ?? '');

        if (!$studentId || !$topicId || !$semesterId) {
            $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin.";
            header("Location: index.php?page=registration-create");
            exit();
        }

        // Constraint: 1 đề tài / học kỳ
        if ($this->regModel->hasAlreadyRegistered($studentId, $semesterId)) {
            $_SESSION['error'] = "Bạn đã đăng ký đề tài trong học kỳ này rồi.";
            header("Location: index.php?page=registration-create");
            exit();
        }

        $this->regModel->create([
            'student_id'          => $studentId,
            'topic_id'            => $topicId,
            'semester_id'         => $semesterId,
            'desired_lecturer_id' => $desiredLec,
            'keywords'            => $keywords,
        ]);

        $_SESSION['success'] = "Đăng ký đề tài thành công. Vui lòng chờ duyệt.";
        header("Location: index.php?page=topic-management&tab=registrations");
        exit();
    }

    /* ──────────────────────────────────────────────
     | UPDATE STATUS  (admin / lecturer only)
     ────────────────────────────────────────────── */
    public function updateStatus() {
        $role = $_SESSION['user']['role'];

        if (!in_array($role, ['admin', 'lecturer'])) {
            // Student cố tình gọi URL này → chặn cứng
            http_response_code(403);
            $_SESSION['error'] = "Bạn không có quyền thực hiện thao tác này.";
            header("Location: index.php?page=registrations");
            exit();
        }

        $id     = (int)($_GET['id']     ?? 0);
        $status = $_GET['status'] ?? '';

        // Whitelist status
        if (!in_array($status, ['approved', 'rejected', 'pending'])) {
            header("Location: index.php?page=registrations");
            exit();
        }

        if ($id) {
            // Lecturer chỉ được duyệt đăng ký chọn mình HOẶC thuộc topic được assign cho mình
            if ($role === 'lecturer') {
                $lecturerId = $_SESSION['user']['lecturer_id'] ?? null;
                $reg = $this->regModel->find($id);
                if (!$reg) {
                    $_SESSION['error'] = "Không tìm thấy đăng ký.";
                    header("Location: index.php?page=registrations");
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
                    header("Location: index.php?page=registrations");
                    exit();
                }
            }
            $this->regModel->updateStatus($id, $status);
            $_SESSION['success'] = "Cập nhật trạng thái thành công.";
        }

        header("Location: index.php?page=registrations");
        exit();
    }
}