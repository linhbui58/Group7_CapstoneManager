<?php

class NotificationController {

    private $notificationModel;

    public function __construct() {
        AuthMiddleware::check();
        $this->notificationModel = new Notification();
    }

    public function index() {
        $userId = $_SESSION['user']['id'];
        $role   = $_SESSION['user']['role'];

        // Admin sees all; others see only their own
        if ($role === 'admin') {
            $notifications = $this->notificationModel->getAll();
        } else {
            $notifications = $this->notificationModel->getByUser($userId);
        }

        require '../app/views/notifications/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->notificationModel->create($_POST);
        }
        redirect('notifications');
    }

    public function read() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $this->notificationModel->markRead($id);
        }
        redirect('notifications');
    }

    public function readAll() {
        $userId = $_SESSION['user']['id'];
        $this->notificationModel->markAllRead($userId);
        redirect('notifications');
    }

    public function delete() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $this->notificationModel->delete($id);
        }
        redirect('notifications');
    }
}
