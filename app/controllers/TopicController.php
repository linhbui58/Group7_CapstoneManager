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

    /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
     | LIST
     | Admin  : táº¥t cáº£ Ä‘á» tÃ i + filter + search
     | Student: táº¥t cáº£ Ä‘á» tÃ i (chá»‰ xem, khÃ´ng sá»­a/xÃ³a)
     | Lecturer: Ä‘á» tÃ i sinh viÃªn Ä‘Äƒng kÃ½ chá»n mÃ¬nh
     â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
    public function index() {
        $role = $_SESSION['user']['role'];

        // Filters tá»« GET
        $search     = trim($_GET['search']      ?? '');
        $filterSem  = (int)($_GET['semester_id'] ?? 0);
        $filterStat = trim($_GET['status']       ?? '');

        if ($role === 'admin') {
            $topics = $this->topicModel->search($search, $filterSem, $filterStat);
        } elseif ($role === 'lecturer') {
            $lecturerId = $_SESSION['user']['lecturer_id'] ?? null;
            $topics = $this->topicModel->getByLecturer($lecturerId, $search, $filterSem, $filterStat);
        } else {
            // student: chá»‰ xem Ä‘á» tÃ i Ä‘Ã£ Ä‘Æ°á»£c duyá»‡t (approved)
            // Náº¿u student tá»± filter status thÃ¬ váº«n giá»›i háº¡n trong approved
            $topics = $this->topicModel->search($search, $filterSem, 'approved');
        }

        $semesters = $this->semesterModel->getAll();
        require '../app/views/topics/index.php';
    }

    /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
     | CREATE FORM
     | Admin  : táº¡o Ä‘á» tÃ i (cÃ³ status field)
     | Student: táº¡o Ä‘á» tÃ i (status tá»± Ä‘á»™ng = pending)
     | Lecturer: khÃ´ng Ä‘Æ°á»£c táº¡o
     â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
    public function create() {
        $role = $_SESSION['user']['role'];
        if ($role === 'lecturer') {
            header("Location: index.php?page=topics");
            exit();
        }
        $semesters = $this->semesterModel->getAll();
        $lecturers = $this->lecturerModel->getAll();
        require '../app/views/topics/create.php';
    }

    /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
     | STORE
     â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
    public function store() {
        $role = $_SESSION['user']['role'];
        if ($role === 'lecturer') {
            header("Location: index.php?page=topics");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            verifyCSRF();
            header("Location: index.php?page=topic-create");
            exit();
        verifyCSRF();
        }

        $title      = trim($_POST['title']       ?? '');
        $semesterId = (int)($_POST['semester_id'] ?? 0);
        $keywords   = trim($_POST['keywords']     ?? '');
        $desc       = trim($_POST['description']  ?? '');

        if (!validateRequired([$title, $desc, $_POST['semester_id']])) {
            $_SESSION['error'] = "Title, description and semester are required.";
            header("Location: index.php?page=topic-create");
            exit();
        }

        // Constraint: khÃ´ng trÃ¹ng tÃªn trong cÃ¹ng há»c ká»³
        if ($this->topicModel->existsInSemester($title, $semesterId)) {
            $_SESSION['error'] = "Äá» tÃ i \"$title\" Ä‘Ã£ tá»“n táº¡i trong há»c ká»³ nÃ y.";
            header("Location: index.php?page=topic-create");
            exit();
        }

        $this->topicModel->create([
            'title'       => $title,
            'description' => $desc,
            'keywords'    => $keywords,
            'semester_id' => $semesterId,
            'created_by'  => $_SESSION['user']['id'],
            // Admin cÃ³ thá»ƒ set status, student luÃ´n pending
            'status'      => ($role === 'admin' && isset($_POST['status']))
                                ? $_POST['status']
                                : 'pending',
        ]);

        LogService::log('create_topic', "Created topic: $title");

        $_SESSION['success'] = "Táº¡o Ä‘á» tÃ i thÃ nh cÃ´ng.";
        header("Location: index.php?page=topic-management&tab=topics");
        exit();
    }

    /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
     | EDIT FORM  (admin only)
     â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
    public function edit() {
        if ($_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?page=topics");
            exit();
        }
        $id    = (int)($_GET['id'] ?? 0);
        $topic = $this->topicModel->find($id);
        if (!$topic) {
            $_SESSION['error'] = "Äá» tÃ i khÃ´ng tá»“n táº¡i.";
            header("Location: index.php?page=topics");
            exit();
        }
        $semesters = $this->semesterModel->getAll();
        require '../app/views/topics/edit.php';
    }

    /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
     | UPDATE  (admin only)
     â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
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

            // Constraint: khÃ´ng trÃ¹ng tÃªn trong cÃ¹ng há»c ká»³ (trá»« chÃ­nh nÃ³)
            if ($this->topicModel->existsInSemester($title, $semesterId, $id)) {
                $_SESSION['error'] = "Äá» tÃ i \"$title\" Ä‘Ã£ tá»“n táº¡i trong há»c ká»³ nÃ y.";
                header("Location: index.php?page=topic-edit&id=$id");
                exit();
            }

            $this->topicModel->update($id, $_POST);
            LogService::log('update_topic', "Updated topic ID: $id");
            $_SESSION['success'] = "Cáº­p nháº­t Ä‘á» tÃ i thÃ nh cÃ´ng.";
        }
        header("Location: index.php?page=topic-management&tab=topics");
        exit();
    }

    /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
     | DELETE  (admin only)
     â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
    public function delete() {
        if ($_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?page=topics");
            exit();
        }
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $this->topicModel->delete($id);
            LogService::log('delete_topic', "Deleted topic ID: $id");
            $_SESSION['success'] = "XÃ³a Ä‘á» tÃ i thÃ nh cÃ´ng.";
        }
        header("Location: index.php?page=topic-management&tab=topics");
        exit();
    }

    /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
     | UPDATE STATUS  (admin / lecturer only)
     â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
    public function updateStatus() {
        $role = $_SESSION['user']['role'];
        if (!in_array($role, ['admin', 'lecturer'])) {
            http_response_code(403);
            $_SESSION['error'] = "Báº¡n khÃ´ng cÃ³ quyá»n thá»±c hiá»‡n thao tÃ¡c nÃ y.";
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
            // Lecturer chá»‰ Ä‘Æ°á»£c duyá»‡t Ä‘á» tÃ i Ä‘Æ°á»£c assign cho mÃ¬nh HOáº¶C sinh viÃªn chá»n mÃ¬nh
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
                    $_SESSION['error'] = "Báº¡n khÃ´ng cÃ³ quyá»n duyá»‡t Ä‘á» tÃ i nÃ y.";
                    header("Location: index.php?page=topics");
                    exit();
                }
            }

            $this->topicModel->updateStatus($id, $status);
            LogService::log('update_topic_status', "Updated status of topic ID: $id to $status");
            $_SESSION['success'] = "Cáº­p nháº­t tráº¡ng thÃ¡i thÃ nh cÃ´ng.";
        }
        header("Location: index.php?page=topic-management&tab=topics");
        exit();
    }
}
