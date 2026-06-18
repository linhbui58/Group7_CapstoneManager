<?php

class ApiTopicController {

    private $model;

    public function __construct() {
        AuthMiddleware::check();
        $this->model = new Topic();
    }

    public function index() {
        jsonResponse(true, "Topics retrieved", $this->model->getAll());
    }

    public function store() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['title'])) {
            jsonResponse(false, "Title required");
        }

        // Ensure required fields
        $data['created_by'] = $_SESSION['user']['id'];
        $data['status']     = $data['status'] ?? 'pending';

        $created = $this->model->create($data);
        jsonResponse(
            (bool)$created,
            $created ? "Created successfully" : "Create failed"
        );
    }

    public function delete() {
        $id      = (int)($_GET['id'] ?? 0);
        $deleted = $id ? $this->model->delete($id) : false;
        jsonResponse(
            (bool)$deleted,
            $deleted ? "Deleted" : "Delete failed"
        );
    }
}
