<?php

class UserController {

    private $userModel;

    public function __construct() {
        AuthMiddleware::check();
        RoleMiddleware::check(['admin']);
        $this->userModel = new User();
    }

    public function index() {
        $users = $this->userModel->getAll();
        require '../app/views/users/index.php';
    }

    public function create() {
        require '../app/views/users/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->userModel->create($_POST);
            $_SESSION['success'] = "User created";
        }
        redirect('users');
    }

    public function show() {
        $id   = (int)($_GET['id'] ?? 0);
        $user = $this->userModel->find($id);
        require '../app/views/users/show.php';
    }

    public function edit() {
        $id   = (int)($_GET['id'] ?? 0);
        $user = $this->userModel->find($id);
        require '../app/views/users/edit.php';
    }

    public function update() {
        $id = (int)($_GET['id'] ?? 0);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $this->userModel->update($id, $_POST);
            $_SESSION['success'] = "User updated";
        }
        redirect('users');
    }

    public function lock() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id === 1) {
            die("Cannot lock admin");
        }
        if ($id) {
            $this->userModel->lock($id);
        }
        redirect('users');
    }

    public function unlock() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $this->userModel->unlock($id);
        }
        redirect('users');
    }

    public function delete() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id === 1) {
            die("Cannot delete admin");
        }
        if ($id) {
            $this->userModel->delete($id);
        }
        redirect('users');
    }
}
