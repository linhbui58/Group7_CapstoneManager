<?php

class LecturerController {

    private $lecturerModel;

    public function __construct() {
        AuthMiddleware::check();
        $this->lecturerModel = new Lecturer();
    }

    public function index() {
        RoleMiddleware::check(['admin']);
        $lecturers = $this->lecturerModel->getAll();
        require '../app/views/lecturers/index.php';
    }

    public function create() {
        RoleMiddleware::check(['admin']);
        require '../app/views/lecturers/create.php';
    }

    /**
     * Admin tạo giảng viên mới — Lecturer::create() sẽ tạo cả user + lecturer row.
     */
    public function store() {
        RoleMiddleware::check(['admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCSRF();
            if ($this->lecturerModel->create($_POST)) {
                $_SESSION['success'] = "Lecturer created successfully";
            } else {
                $_SESSION['error'] = "Failed to create lecturer. Email may already exist.";
            }
        }
        redirect('lecturers');
    }

    public function show() {
        RoleMiddleware::check(['admin', 'lecturer']);
        $id       = (int)($_GET['id'] ?? 0);
        $role     = $_SESSION['user']['role'] ?? '';
        $lecturerId = $_SESSION['user']['lecturer_id'] ?? null;
        
        if ($role === 'lecturer' && $id !== (int)$lecturerId) {
            abort(403, "Bạn không có quyền xem thông tin của giảng viên này.");
        }
        $lecturer = $this->lecturerModel->find($id);
        require '../app/views/lecturers/show.php';
    }

    public function edit() {
        RoleMiddleware::check(['admin', 'lecturer']);
        $id       = (int)($_GET['id'] ?? 0);
        $role     = $_SESSION['user']['role'] ?? '';
        $lecturerId = $_SESSION['user']['lecturer_id'] ?? null;
        
        if ($role === 'lecturer' && $id !== (int)$lecturerId) {
            abort(403, "Bạn không có quyền chỉnh sửa thông tin của giảng viên này.");
        }
        $lecturer = $this->lecturerModel->find($id);
        require '../app/views/lecturers/edit.php';
    }

    public function update() {
        RoleMiddleware::check(['admin', 'lecturer']);
        $id = (int)($_GET['id'] ?? 0);
        $role     = $_SESSION['user']['role'] ?? '';
        $lecturerId = $_SESSION['user']['lecturer_id'] ?? null;
        
        if ($role === 'lecturer' && $id !== (int)$lecturerId) {
            abort(403, "Bạn không có quyền chỉnh sửa thông tin của giảng viên này.");
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            verifyCSRF();
            $this->lecturerModel->update($id, $_POST);
            $_SESSION['success'] = "Lecturer updated";
        }
        redirect('lecturers');
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            abort(405, "Method Not Allowed");
        }
        verifyCSRF();
        RoleMiddleware::check(['admin']);
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $this->lecturerModel->delete($id);
            $_SESSION['success'] = "Lecturer deleted";
        }
        redirect('lecturers');
    }
}
