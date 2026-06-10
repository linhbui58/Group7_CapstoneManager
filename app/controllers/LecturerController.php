<?php

class LecturerController {

    private $lecturerModel;

    public function __construct() {
        AuthMiddleware::check();
        $this->lecturerModel = new Lecturer();
    }

    public function index() {
        $lecturers = $this->lecturerModel->getAll();
        require '../app/views/lecturers/index.php';
    }

    public function create() {
        require '../app/views/lecturers/create.php';
    }

    /**
     * Admin tạo giảng viên mới — Lecturer::create() sẽ tạo cả user + lecturer row.
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->lecturerModel->create($_POST)) {
                $_SESSION['success'] = "Lecturer created successfully";
            } else {
                $_SESSION['error'] = "Failed to create lecturer. Email may already exist.";
            }
        }
        redirect('lecturers');
    }

    public function show() {
        $id       = (int)($_GET['id'] ?? 0);
        $lecturer = $this->lecturerModel->find($id);
        require '../app/views/lecturers/show.php';
    }

    public function edit() {
        $id       = (int)($_GET['id'] ?? 0);
        $lecturer = $this->lecturerModel->find($id);
        require '../app/views/lecturers/edit.php';
    }

    public function update() {
        $id = (int)($_GET['id'] ?? 0);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $this->lecturerModel->update($id, $_POST);
            $_SESSION['success'] = "Lecturer updated";
        }
        redirect('lecturers');
    }

    public function delete() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $this->lecturerModel->delete($id);
            $_SESSION['success'] = "Lecturer deleted";
        }
        redirect('lecturers');
    }
}
