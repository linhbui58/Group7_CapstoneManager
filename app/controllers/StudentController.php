<?php

class StudentController {

    private $studentModel;

    public function __construct() {
        AuthMiddleware::check();
        $this->studentModel = new Student();
    }

    public function index() {
        RoleMiddleware::check(['admin']);
        $students = $this->studentModel->getAll();
        require '../app/views/students/index.php';
    }

    public function show() {
        RoleMiddleware::check(['admin', 'student']);
        $id      = (int)($_GET['id'] ?? 0);
        $role    = $_SESSION['user']['role'] ?? '';
        $studentId = $_SESSION['user']['student_id'] ?? null;
        
        if ($role === 'student' && $id !== (int)$studentId) {
            abort(403, "Bạn không có quyền xem thông tin của sinh viên này.");
        }

        $student = $this->studentModel->find($id); // đã JOIN users
        require '../app/views/students/show.php';
    }

    public function create() {
        RoleMiddleware::check(['admin']);
        require '../app/views/students/create.php';
    }

    public function store() {
        RoleMiddleware::check(['admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCSRF();
            // Kiểm tra email trùng
            $userModel = new User();
            if ($userModel->findByEmail($_POST['email'] ?? '')) {
                $_SESSION['error'] = "Email đã tồn tại.";
                redirect('student-create');
            }

            $result = $this->studentModel->createWithUser($_POST);
            if ($result) {
                $_SESSION['success'] = "Tạo sinh viên thành công.";
            } else {
                $_SESSION['error'] = "Tạo sinh viên thất bại. Email có thể đã tồn tại.";
            }
        }
        redirect('students');
    }

    public function edit() {
        RoleMiddleware::check(['admin', 'student']);
        $id      = (int)($_GET['id'] ?? 0);
        $role    = $_SESSION['user']['role'] ?? '';
        $studentId = $_SESSION['user']['student_id'] ?? null;
        
        if ($role === 'student' && $id !== (int)$studentId) {
            abort(403, "Bạn không có quyền chỉnh sửa thông tin của sinh viên này.");
        }

        $student = $this->studentModel->find($id); // đã JOIN users
        require '../app/views/students/edit.php';
    }

    public function update() {
        RoleMiddleware::check(['admin', 'student']);
        $id = (int)($_GET['id'] ?? 0);
        $role = $_SESSION['user']['role'] ?? '';
        $studentId = $_SESSION['user']['student_id'] ?? null;
        
        if ($role === 'student' && $id !== (int)$studentId) {
            abort(403, "Bạn không có quyền chỉnh sửa thông tin của sinh viên này.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            verifyCSRF();
            $this->studentModel->update($id, $_POST);
            $_SESSION['success'] = "Cập nhật sinh viên thành công.";
        }
        redirect('students');
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit("Method Not Allowed");
        }
        verifyCSRF();
        RoleMiddleware::check(['admin']);
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            // Xóa user -> cascade xóa student
            $student = $this->studentModel->find($id);
            if ($student) {
                $userModel = new User();
                $userModel->delete($student['user_id']);
            }
            $_SESSION['success'] = "Đã xóa sinh viên.";
        }
        redirect('students');
    }
}
