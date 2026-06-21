<?php
class AssignmentController {
    private $assignmentModel;
    private $topicModel;
    private $lecturerModel;

    public function __construct() {
        AuthMiddleware::check();
        RoleMiddleware::check(['admin']);
        $this->assignmentModel = new TopicAssignment();
        $this->topicModel = new Topic();
        $this->lecturerModel = new Lecturer();
    }

    public function index() {
        $assignments = $this->assignmentModel->getAll();
        require '../app/views/assignments/index.php';
    }

    public function create() {
        $topics = $this->topicModel->getTopicsNeedingAssignment();
        $lecturers = $this->lecturerModel->getAll();
        require '../app/views/assignments/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            verifyCSRF();
            $lecturerId = $_POST['lecturer_id'] ?? null;
            if ($lecturerId && !WorkloadService::canAssign($lecturerId)) {
                $_SESSION['error'] = "Giảng viên đã đạt giới hạn sinh viên hướng dẫn.";
                header("Location: index.php?page=assignment-create");
                exit();
            }

            try {
                if ($this->assignmentModel->create($_POST)) {
                    LogService::log('assign_topic', "Assigned Topic to Lecturer ID: " . $_POST['lecturer_id']);
                    $_SESSION['success'] = "Phân công thành công.";
                } else {
                    $_SESSION['error'] = "Không thể phân công. Đề tài có thể đã được phân công rồi.";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Lỗi: " . $e->getMessage();
            }
        }
        header("Location: index.php?page=assignments");
        exit();
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) abort(404);
        $assignment = $this->assignmentModel->find($id);
        if (!$assignment) abort(404);
        $topics = $this->topicModel->getTopicsNeedingAssignment();
        $lecturers = $this->lecturerModel->getAll();
        require '../app/views/assignments/edit.php';
    }

    public function update() {
        $id = $_GET['id'] ?? null;
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $id) {
            verifyCSRF();
            $lecturerId = $_POST['lecturer_id'] ?? null;
            $assignment = $this->assignmentModel->find($id);
            if (!$assignment) abort(404);

            if ($lecturerId && $assignment['lecturer_id'] != $lecturerId) {
                if (!WorkloadService::canAssign($lecturerId)) {
                    $_SESSION['error'] = "Lecturer quota exceeded.";
                    header("Location: index.php?page=assignment-edit&id=" . $id);
                    exit();
                }
            }

            if ($this->assignmentModel->update($id, $_POST)) {
                LogService::log('update_assignment', "Updated assignment ID: $id");
                $_SESSION['success'] = "Assignment updated successfully.";
                header("Location: index.php?page=assignments");
                exit();
            }
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit("Method Not Allowed");
        }
        verifyCSRF();
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->assignmentModel->delete($id);
            LogService::log('delete_assignment', "Deleted assignment ID: $id");
        }
        header("Location: index.php?page=assignments");
        exit();
    }
}
