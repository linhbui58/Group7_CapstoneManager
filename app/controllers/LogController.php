<?php

class LogController {
    private $logModel;

    public function __construct() {
        AuthMiddleware::check();
        // Chỉ Admin mới có quyền xem Log
        if ($_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?page=dashboard");
            exit();
        }
        $this->logModel = new SystemLog();
    }

    public function index() {
        $logs = $this->logModel->getAll();
        require '../app/views/logs/index.php';
    }
}