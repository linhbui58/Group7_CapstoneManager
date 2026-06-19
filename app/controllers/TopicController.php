<?php
class TopicController {

    private $topicModel;
    private $semesterModel;
    private $lecturerModel;

    public function __construct() {
        AuthMiddleware::check();
        $this->topicModel    = new Topic();
        $this->semesterModel = new Semester();
        $this->lecturerModel = new Lecturer();
    }

    /* ──────────────────────────────────────────────────────────
     | CREATE FORM
     | Admin  : tạo đề tài (có status field)
     | Student: tạo đề tài (status tự động = pending)
     | Lecturer: không được tạo
     ────────────────────────────────────────────────────────── */
    public function create() {
        $role = $_SESSION['user']['role'];
        if ($role === 'lecturer') {
            header("Location: index.php?page=topic-management");
            exit();
        }
        $semesters = $this->semesterModel->getAll();
        require '../app/views/topics/create.php';
    }

    /* ──────────────────────────────────────────────────────────
     | STORE
     ────────────────────────────────────────────────────────── */
    public function store() {
        $role = $_SESSION['user']['role'];
        if ($role === 'lecturer') {
            header("Location: index.php?page=topics");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=topic-create");
            exit();
        }
        verifyCSRF();

        $title      = trim($_POST['title']       ?? '');
        $semesterId = (int)($_POST['semester_id'] ?? 0);
        $keywords   = trim($_POST['keywords']     ?? '');
        $desc       = trim($_POST['description']  ?? '');

        if (!validateRequired([$title, $desc, $_POST['semester_id']])) {
            $_SESSION['error'] = "Title, description and semester are required.";
            header("Location: index.php?page=topic-create");
            exit();
        }

        // Constraint: không trùng tên trong cùng học kỳ
        if ($this->topicModel->existsInSemester($title, $semesterId)) {
            $_SESSION['error'] = "Đề tài \"$title\" đã tồn tại trong học kỳ này.";
            header("Location: index.php?page=topic-create");
            exit();
        }

        $this->topicModel->create([
            'title'       => $title,
            'description' => $desc,
            'keywords'    => $keywords,
            'semester_id' => $semesterId,
            'created_by'  => $_SESSION['user']['id'],
            // Admin có thể set status, student luôn pending
            'status'      => ($role === 'admin' && isset($_POST['status']))
                                ? $_POST['status']
                                : 'pending',
        ]);

        LogService::log('create_topic', "Created topic: $title");

        $_SESSION['success'] = "Tạo đề tài thành công.";
        header("Location: index.php?page=topic-management&tab=topics");
        exit();
    }

    /* ──────────────────────────────────────────────────────────
     | EDIT FORM  (admin only)
     ────────────────────────────────────────────────────────── */
    public function edit() {
        if ($_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?page=topics");
            exit();
        }
        $id    = (int)($_GET['id'] ?? 0);
        $topic = $this->topicModel->find($id);
        if (!$topic) {
            $_SESSION['error'] = "Đề tài không tồn tại.";
            header("Location: index.php?page=topics");
            exit();
        }
        $semesters = $this->semesterModel->getAll();
        require '../app/views/topics/edit.php';
    }

    /* ──────────────────────────────────────────────────────────
     | UPDATE  (admin only)
     ────────────────────────────────────────────────────────── */
    public function update() {
        if ($_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?page=topics");
            exit();
        }
        $id = (int)($_GET['id'] ?? 0);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            verifyCSRF();
            $title      = trim($_POST['title']       ?? '');
            $semesterId = (int)($_POST['semester_id'] ?? 0);
            $desc       = trim($_POST['description']  ?? '');

            if (!validateRequired([$title, $desc, $_POST['semester_id']])) {
                $_SESSION['error'] = "Title, description and semester are required.";
                header("Location: index.php?page=topic-edit&id=$id");
                exit();
            }

            // Constraint: không trùng tên trong cùng học kỳ (trừ chính nó)
            if ($this->topicModel->existsInSemester($title, $semesterId, $id)) {
                $_SESSION['error'] = "Đề tài \"$title\" đã tồn tại trong học kỳ này.";
                header("Location: index.php?page=topic-edit&id=$id");
                exit();
            }

            $this->topicModel->update($id, $_POST);
            LogService::log('update_topic', "Updated topic ID: $id");
            $_SESSION['success'] = "Cập nhật đề tài thành công.";
        }
        header("Location: index.php?page=topic-management&tab=topics");
        exit();
    }

    /* ──────────────────────────────────────────────────────────
     | DELETE  (admin only)
     ────────────────────────────────────────────────────────── */
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit("Method Not Allowed");
        }
        verifyCSRF();
        if ($_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?page=topics");
            exit();
        }
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $this->topicModel->delete($id);
            LogService::log('delete_topic', "Deleted topic ID: $id");
            $_SESSION['success'] = "Xóa đề tài thành công.";
        }
        header("Location: index.php?page=topic-management&tab=topics");
        exit();
    }

    /* ──────────────────────────────────────────────────────────
     | UPDATE STATUS  (admin / lecturer only)
     ────────────────────────────────────────────────────────── */
    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit("Method Not Allowed");
        }
        verifyCSRF();
        $role = $_SESSION['user']['role'];
        if (!in_array($role, ['admin', 'lecturer'])) {
            http_response_code(403);
            $_SESSION['error'] = "Bạn không có quyền thực hiện thao tác này.";
            header("Location: index.php?page=topics");
            exit();
        }

        $id     = (int)($_GET['id']     ?? 0);
        $status = $_GET['status'] ?? '';

        if (!in_array($status, ['approved', 'rejected', 'pending'])) {
            header("Location: index.php?page=topics");
            exit();
        }

        if ($id) {
            // Lecturer chỉ được duyệt đề tài được assign cho mình HOẶC sinh viên chọn mình
            if ($role === 'lecturer') {
                $lecturerId = $_SESSION['user']['lecturer_id'] ?? null;
                $db   = Database::getInstance()->getConnection();
                $stmt = $db->prepare(
                    "SELECT COUNT(*) FROM topic_assignments
                     WHERE topic_id = ? AND lecturer_id = ?"
                );
                $stmt->execute([$id, $lecturerId]);
                $isAssigned = (int)$stmt->fetchColumn() > 0;

                $stmt2 = $db->prepare(
                    "SELECT COUNT(*) FROM topic_registrations
                     WHERE topic_id = ? AND desired_lecturer_id = ?"
                );
                $stmt2->execute([$id, $lecturerId]);
                $isDesired = (int)$stmt2->fetchColumn() > 0;

                if (!$isAssigned && !$isDesired) {
                    $_SESSION['error'] = "Bạn không có quyền duyệt đề tài này.";
                    header("Location: index.php?page=topics");
                    exit();
                }
            }

            $this->topicModel->updateStatus($id, $status);
            LogService::log('update_topic_status', "Updated status of topic ID: $id to $status");
            $_SESSION['success'] = "Cập nhật trạng thái thành công.";
        }
        header("Location: index.php?page=topic-management&tab=topics");
        exit();
    }
}
