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
        $role    = $_SESSION['user']['role'] ?? '';
        $studentId = $_SESSION['user']['student_id'] ?? null;
        
        if ($role === 'student' && $id !== $studentId) {
            abort(403, "Bạn không có quyền xem thông tin của sinh viên này.");
        }

        $student = $this->studentModel->find($id); // Ä‘Ã£ JOIN users
        require '../app/views/students/show.php';
    }

    public function create() {
        require '../app/views/students/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCSRF();
            // Kiá»ƒm tra email trÃ¹ng
            $userModel = new User();
            if ($userModel->findByEmail($_POST['email'] ?? '')) {
                $_SESSION['error'] = "Email Ä‘Ã£ tá»“n táº¡i.";
                redirect('student-create');
            }

            $result = $this->studentModel->createWithUser($_POST);
            if ($result) {
                $_SESSION['success'] = "Táº¡o sinh viÃªn thÃ nh cÃ´ng.";
            } else {
                $_SESSION['error'] = "Táº¡o sinh viÃªn tháº¥t báº¡i. Email cÃ³ thá»ƒ Ä‘Ã£ tá»“n táº¡i.";
            }
        }
        redirect('students');
    }

    public function edit() {
        $id      = (int)($_GET['id'] ?? 0);
        $role    = $_SESSION['user']['role'] ?? '';
        $studentId = $_SESSION['user']['student_id'] ?? null;
        
        if ($role === 'student' && $id !== $studentId) {
            abort(403, "Bạn không có quyền chỉnh sửa thông tin của sinh viên này.");
        }

        $student = $this->studentModel->find($id); // Ä‘Ã£ JOIN users
        require '../app/views/students/edit.php';
    }

    public function update() {
        $id = (int)($_GET['id'] ?? 0);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            verifyCSRF();
            $this->studentModel->update($id, $_POST);
            $_SESSION['success'] = "Cáº­p nháº­t sinh viÃªn thÃ nh cÃ´ng.";
        }
        redirect('students');
    }

    public function delete() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            // XÃ³a user â†’ cascade xÃ³a student
            $student = $this->studentModel->find($id);
            if ($student) {
                $userModel = new User();
                $userModel->delete($student['user_id']);
            }
            $_SESSION['success'] = "ÄÃ£ xÃ³a sinh viÃªn.";
        }
        redirect('students');
    }
}
