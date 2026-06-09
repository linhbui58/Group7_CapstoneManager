<?php
class AssignmentController {
    private $assignmentModel;
    private $topicModel;
    private $lecturerModel;

    public function __construct() {
        AuthMiddleware::check();
        $this->assignmentModel = new TopicAssignment();
        $this->topicModel = new Topic();
        $this->lecturerModel = new Lecturer();
    }

    public function index() {
        $assignments = $this->assignmentModel->getAll();
        require '../app/views/assignments/index.php';
    }

    public function create() {
        $topics = $this->topicModel->getAll();
        $lecturers = $this->lecturerModel->getAll();
        require '../app/views/assignments/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->assignmentModel->create($_POST)) {
                header("Location: index.php?page=assignments");
                exit();
            }
        }
    }

    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->assignmentModel->delete($id);
        }
        header("Location: index.php?page=assignments");
        exit();
    }
}