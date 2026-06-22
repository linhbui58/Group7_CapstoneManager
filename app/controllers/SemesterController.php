<?php

class SemesterController {

    private $semesterModel;

    public function __construct() {
        AuthMiddleware::check();
        RoleMiddleware::check(['admin']);
        $this->semesterModel = new Semester();
    }

    public function index() {
        $semesters = $this->semesterModel->getAll();
        require '../app/views/semesters/index.php';
    }

    public function create() {
        require '../app/views/semesters/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCSRF();
            if ($this->semesterModel->create($_POST)) {
                $_SESSION['success'] = "Semester created";
            } else {
                $_SESSION['error'] = "Failed to create semester";
            }
        }
        redirect('semesters');
    }

    public function edit() {
        $id       = (int)($_GET['id'] ?? 0);
        $semester = $this->semesterModel->find($id);
        require '../app/views/semesters/edit.php';
    }

    public function update() {
        $id = (int)($_GET['id'] ?? 0);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            verifyCSRF();
            $this->semesterModel->update($id, $_POST);
            $_SESSION['success'] = "Semester updated";
        }
        redirect('semesters');
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            abort(405, "Method Not Allowed");
        }
        verifyCSRF();
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $this->semesterModel->delete($id);
            $_SESSION['success'] = "Semester deleted";
        }
        redirect('semesters');
    }
}
