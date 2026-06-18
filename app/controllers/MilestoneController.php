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
        RoleMiddleware::check(['admin']);
        $semesters = $this->semesterModel->getAll();
        require '../app/views/milestones/create.php';
    }

    public function store() {
        RoleMiddleware::check(['admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verifyCSRF();
            $semesterId = (int)($_POST['semester_id'] ?? 0);
            $deadline = $_POST['deadline'] ?? '';
            $semester = $this->semesterModel->find($semesterId);
            
            if ($semester && $deadline) {
                $deadlineTime = strtotime($deadline);
                $startTime = strtotime($semester['start_date'] . ' 00:00:00');
                $endTime = strtotime($semester['end_date'] . ' 23:59:59');
                
                if ($deadlineTime < $startTime || $deadlineTime > $endTime) {
                    $_SESSION['error'] = "Hạn chót phải nằm trong thời gian của học kỳ (" . date('d/m/Y', $startTime) . " - " . date('d/m/Y', $endTime) . ").";
                    redirect('milestone-create');
                    return;
                }
            }

            if ($this->milestoneModel->create($_POST)) {
                $_SESSION['success'] = "Đã tạo cột mốc thành công.";
            } else {
                $_SESSION['error'] = "Thêm cột mốc thất bại.";
            }
        }
        redirect('milestones');
    }

    public function edit() {
        RoleMiddleware::check(['admin']);
        $id        = (int)($_GET['id'] ?? 0);
        $milestone = $this->milestoneModel->find($id);
        $semesters = $this->semesterModel->getAll();
        require '../app/views/milestones/edit.php';
    }

    public function update() {
        RoleMiddleware::check(['admin']);
        $id = (int)($_GET['id'] ?? 0);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            verifyCSRF();
            $semesterId = (int)($_POST['semester_id'] ?? 0);
            $deadline = $_POST['deadline'] ?? '';
            $semester = $this->semesterModel->find($semesterId);
            
            if ($semester && $deadline) {
                $deadlineTime = strtotime($deadline);
                $startTime = strtotime($semester['start_date'] . ' 00:00:00');
                $endTime = strtotime($semester['end_date'] . ' 23:59:59');
                
                if ($deadlineTime < $startTime || $deadlineTime > $endTime) {
                    $_SESSION['error'] = "Hạn chót phải nằm trong thời gian của học kỳ (" . date('d/m/Y', $startTime) . " - " . date('d/m/Y', $endTime) . ").";
                    redirect("milestone-edit&id=$id");
                    return;
                }
            }

            if ($this->milestoneModel->update($id, $_POST)) {
                $_SESSION['success'] = "Đã cập nhật cột mốc.";
            } else {
                $_SESSION['error'] = "Cập nhật thất bại.";
            }
        }
        redirect('milestones');
    }

    public function delete() {
        RoleMiddleware::check(['admin']);
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $this->milestoneModel->delete($id);
            $_SESSION['success'] = "Milestone deleted";
        }
        redirect('milestones');
    }
}
