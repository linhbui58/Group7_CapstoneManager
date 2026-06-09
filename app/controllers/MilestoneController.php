<?php

class MilestoneController {

    private $milestoneModel;
    private $semesterModel;

    public function __construct() {
        AuthMiddleware::check();
        $this->milestoneModel = new Milestone();
        $this->semesterModel  = new Semester();
    }

    public function index() {
        $milestones = $this->milestoneModel->getAll();
        require '../app/views/milestones/index.php';
    }

    public function create() {
        $semesters = $this->semesterModel->getAll();
        require '../app/views/milestones/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->milestoneModel->create($_POST)) {
                $_SESSION['success'] = "Milestone created";
            } else {
                $_SESSION['error'] = "Failed to create milestone";
            }
        }
        redirect('milestones');
    }

    public function edit() {
        $id        = (int)($_GET['id'] ?? 0);
        $milestone = $this->milestoneModel->find($id);
        $semesters = $this->semesterModel->getAll();
        require '../app/views/milestones/edit.php';
    }

    public function update() {
        $id = (int)($_GET['id'] ?? 0);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            if ($this->milestoneModel->update($id, $_POST)) {
                $_SESSION['success'] = "Milestone updated";
            } else {
                $_SESSION['error'] = "Failed to update milestone";
            }
        }
        redirect('milestones');
    }

    public function delete() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $this->milestoneModel->delete($id);
            $_SESSION['success'] = "Milestone deleted";
        }
        redirect('milestones');
    }
}
