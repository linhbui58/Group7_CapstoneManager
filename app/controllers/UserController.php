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
            verifyCSRF();
            if (!validateRequired([$_POST['email'], $_POST['password'], $_POST['role']])) {
                $_SESSION['error'] = "All fields are required.";
                redirect('user-create');
                return;
            }
            if (!validateEmail($_POST['email'])) {
                $_SESSION['error'] = "Invalid email format.";
                redirect('user-create');
                return;
            }
            if (!validatePassword($_POST['password'])) {
                $_SESSION['error'] = "Password must be at least 6 characters.";
                redirect('user-create');
                return;
            }
            if ($this->userModel->emailExists($_POST['email'])) {
                $_SESSION['error'] = "Email already exists.";
                redirect('user-create');
                return;
            }

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
            verifyCSRF();
            if (!validateRequired([$_POST['email'], $_POST['role']])) {
                $_SESSION['error'] = "Email and role are required.";
                header("Location: " . BASE_URL . "index.php?page=user-edit&id=$id");
                exit();
            }
            if (!validateEmail($_POST['email'])) {
                $_SESSION['error'] = "Invalid email format.";
                header("Location: " . BASE_URL . "index.php?page=user-edit&id=$id");
                exit();
            }
            
            $existing = $this->userModel->findByEmail($_POST['email']);
            if ($existing && $existing['id'] != $id) {
                $_SESSION['error'] = "Email already exists.";
                header("Location: " . BASE_URL . "index.php?page=user-edit&id=$id");
                exit();
            }

            $this->userModel->update($id, $_POST);
            $_SESSION['success'] = "User updated";
        }
        redirect('users');
    }

    public function lock() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            abort(405, "Method Not Allowed");
        }
        verifyCSRF();
        $id = (int)($_GET['id'] ?? 0);
        if ($id === 1) {
            abort(403, "Cannot lock admin");
        }
        if ($id) {
            $this->userModel->lock($id);
        }
        redirect('users');
    }

    public function unlock() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            abort(405, "Method Not Allowed");
        }
        verifyCSRF();
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $this->userModel->unlock($id);
        }
        redirect('users');
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            abort(405, "Method Not Allowed");
        }
        verifyCSRF();
        $id = (int)($_GET['id'] ?? 0);
        if ($id === 1) {
            abort(403, "Cannot delete admin");
        }
        if ($id) {
            $this->userModel->delete($id);
        }
        redirect('users');
    }
}
