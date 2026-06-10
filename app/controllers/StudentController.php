<?php

class StudentController {

    private $studentModel;

    public function __construct() {
        AuthMiddleware::check();
        $this->studentModel = new Student();
    }

    public function index() {
        $students = $this->studentModel->getAll();
        require '../app/views/students/index.php';
    }

    public function show() {
        $id      = (int)($_GET['id'] ?? 0);
        $student = $this->studentModel->find($id); // đã JOIN users
        require '../app/views/students/show.php';
    }

    public function create() {
        require '../app/views/students/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        $id      = (int)($_GET['id'] ?? 0);
        $student = $this->studentModel->find($id); // đã JOIN users
        require '../app/views/students/edit.php';
    }

    public function update() {
        $id = (int)($_GET['id'] ?? 0);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $this->studentModel->update($id, $_POST);
            $_SESSION['success'] = "Cập nhật sinh viên thành công.";
        }
        redirect('students');
    }

    public function delete() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            // Xóa user → cascade xóa student
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
