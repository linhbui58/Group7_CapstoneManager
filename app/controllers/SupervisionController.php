<?php
class SupervisionController {

    private $supervisionModel;

    public function __construct() {
        AuthMiddleware::check();
        // Chỉ admin được phép thao tác phân công
        if ($_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error'] = "Bạn không có quyền truy cập trang này.";
            header("Location: index.php?page=dashboard");
            exit();
        }
        $this->supervisionModel = new SupervisionAssignment();
    }

    public function index() {
        $assignments = $this->supervisionModel->getAll();
        require '../app/views/supervisions/index.php';
    }

    public function create() {
        $studentModel  = new Student();
        $lecturerModel = new Lecturer();
        $semesterModel = new Semester();

        $students  = $studentModel->getAll();
        $lecturers = $lecturerModel->getAll();
        $semesters = $semesterModel->getAll();

        // Lọc bớt sinh viên đã có phân công trong kỳ hiện tại
        // Sẽ xử lý bằng Ajax hoặc Javascript ở form create.php nếu cần, 
        // hoặc load toàn bộ ra và xử lý ở Javascript.

        require '../app/views/supervisions/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=supervision-create");
            exit();
        }
        verifyCSRF();

        $studentId  = (int)($_POST['student_id'] ?? 0);
        $lecturerId = (int)($_POST['lecturer_id'] ?? 0);
        $semesterId = (int)($_POST['semester_id'] ?? 0);

        if (!$studentId || !$lecturerId || !$semesterId) {
            $_SESSION['error'] = "Vui lòng chọn đầy đủ Sinh viên, Giảng viên và Học kỳ.";
            header("Location: index.php?page=supervision-create");
            exit();
        }

        // Validate khoa
        $studentModel = new Student();
        $lecturerModel = new Lecturer();
        $student = $studentModel->find($studentId);
        $lecturer = $lecturerModel->find($lecturerId);

        if (!$student || !$lecturer) {
             $_SESSION['error'] = "Dữ liệu không hợp lệ.";
             header("Location: index.php?page=supervision-create");
             exit();
        }

        if ($student['faculty'] !== $lecturer['faculty']) {
            $_SESSION['error'] = "Giảng viên và Sinh viên phải cùng khoa ({$student['faculty']}).";
            header("Location: index.php?page=supervision-create");
            exit();
        }

        // Kiểm tra xem sinh viên đã được phân công trong học kỳ này chưa
        $existing = $this->supervisionModel->findByStudentAndSemester($studentId, $semesterId);
        if ($existing) {
            $_SESSION['error'] = "Sinh viên này đã được phân công giảng viên trong học kỳ đã chọn.";
            header("Location: index.php?page=supervision-create");
            exit();
        }

        $this->supervisionModel->create([
            'student_id'  => $studentId,
            'lecturer_id' => $lecturerId,
            'semester_id' => $semesterId,
            'assigned_by' => $_SESSION['user']['id']
        ]);

        require_once '../app/models/Notification.php';
        $notifModel = new Notification();
        if ($student && !empty($student['user_id'])) {
            $notifModel->create([
                'user_id' => $student['user_id'],
                'content' => "Bạn đã được phân công Giảng viên hướng dẫn: {$lecturer['full_name']} trong học kỳ này.",
                'type'    => 'info'
            ]);
        }
        if ($lecturer && !empty($lecturer['user_id'])) {
            $notifModel->create([
                'user_id' => $lecturer['user_id'],
                'content' => "Bạn đã được phân công hướng dẫn sinh viên: {$student['full_name']} trong học kỳ này.",
                'type'    => 'info'
            ]);
        }

        LogService::log('create_supervision', "Assigned student $studentId to lecturer $lecturerId for semester $semesterId");

        $_SESSION['success'] = "Phân công giảng viên hướng dẫn thành công.";
        header("Location: index.php?page=supervisions");
        exit();
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit("Method Not Allowed");
        }
        verifyCSRF();

        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $this->supervisionModel->delete($id);
            LogService::log('delete_supervision', "Deleted supervision assignment ID: $id");
            $_SESSION['success'] = "Xóa phân công thành công.";
        }

        header("Location: index.php?page=supervisions");
        exit();
    }
}
