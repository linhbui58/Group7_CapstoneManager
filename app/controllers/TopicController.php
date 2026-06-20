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
            header("Location: index.php?page=topic-management&tab=topics");
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

        if ($role === 'student') {
            $studentId = $_SESSION['user']['student_id'] ?? null;
            if (!$studentId) {
                $studentModel = new Student();
                $student = $studentModel->findByUserId($_SESSION['user']['id']);
                if ($student) {
                    $studentId = $student['id'];
                }
            }

            require_once '../app/models/SupervisionAssignment.php';
            $supervisionModel = new SupervisionAssignment();
            $assignment = $supervisionModel->findByStudentAndSemester($studentId, $semesterId);
            
            if (!$assignment) {
                $_SESSION['error'] = "Bạn cần được phân công giảng viên trước khi đề xuất đề tài mới trong học kỳ này.";
                header("Location: index.php?page=topic-create");
                exit();
            }

            // Also check if they already have a registered topic?
            require_once '../app/models/TopicRegistration.php';
            $regModel = new TopicRegistration();
            if ($regModel->hasRegistered($studentId, $semesterId)) {
                $_SESSION['error'] = "Bạn đã đăng ký chính thức 1 đề tài trong học kỳ này, không thể đề xuất thêm.";
                header("Location: index.php?page=topic-create");
                exit();
            }
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
        $id    = (int)($_GET['id'] ?? 0);
        $topic = $this->topicModel->find($id);
        if (!$topic) {
            $_SESSION['error'] = "Đề tài không tồn tại.";
            header("Location: index.php?page=topic-management&tab=topics");
            exit();
        }

        $role = $_SESSION['user']['role'];
        if ($role !== 'admin') {
            if ($role === 'student') {
                if ($topic['created_by'] != $_SESSION['user']['id'] || $topic['status'] !== 'pending') {
                    $_SESSION['error'] = "Bạn không có quyền sửa đề tài này.";
                    header("Location: index.php?page=topic-management&tab=topics");
                    exit();
                }
            } else {
                header("Location: index.php?page=topic-management&tab=topics");
                exit();
            }
        }
        
        $semesters = $this->semesterModel->getAll();
        require '../app/views/topics/edit.php';
    }

    /* ──────────────────────────────────────────────────────────
     | UPDATE  (admin only)
     ────────────────────────────────────────────────────────── */
    public function update() {
        $id = (int)($_GET['id'] ?? 0);
        $topic = $this->topicModel->find($id);

        if (!$topic) {
            $_SESSION['error'] = "Đề tài không tồn tại.";
            header("Location: index.php?page=topic-management&tab=topics");
            exit();
        }

        $role = $_SESSION['user']['role'];
        if ($role !== 'admin') {
            if ($role === 'student') {
                if ($topic['created_by'] != $_SESSION['user']['id'] || $topic['status'] !== 'pending') {
                    $_SESSION['error'] = "Bạn không có quyền sửa đề tài này.";
                    header("Location: index.php?page=topic-management&tab=topics");
                    exit();
                }
            } else {
                header("Location: index.php?page=topic-management&tab=topics");
                exit();
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            verifyCSRF();
            $title      = trim($_POST['title']       ?? '');
            $semesterId = (int)($_POST['semester_id'] ?? 0);
            $desc       = trim($_POST['description']  ?? '');
            $keywords   = trim($_POST['keywords']  ?? '');

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

            // Preserve status if student
            $status = ($role === 'admin' && isset($_POST['status'])) ? $_POST['status'] : $topic['status'];

            $this->topicModel->update($id, [
                'title' => $title,
                'description' => $desc,
                'keywords' => $keywords,
                'semester_id' => $semesterId,
                'status' => $status
            ]);
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
        
        $id = (int)($_GET['id'] ?? 0);
        $topic = $this->topicModel->find($id);

        if (!$topic) {
            $_SESSION['error'] = "Đề tài không tồn tại.";
            header("Location: index.php?page=topic-management&tab=topics");
            exit();
        }

        $role = $_SESSION['user']['role'];
        if ($role !== 'admin') {
            if ($role === 'student') {
                if ($topic['created_by'] != $_SESSION['user']['id'] || $topic['status'] !== 'pending') {
                    $_SESSION['error'] = "Bạn không có quyền xóa đề tài này.";
                    header("Location: index.php?page=topic-management&tab=topics");
                    exit();
                }
            } else {
                header("Location: index.php?page=topic-management&tab=topics");
                exit();
            }
        }

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
            header("Location: index.php?page=topic-management&tab=topics");
            exit();
        }

        $id     = (int)($_GET['id']     ?? 0);
        $status = $_GET['status'] ?? '';

        if (!in_array($status, ['approved', 'rejected', 'pending'])) {
            header("Location: index.php?page=topic-management&tab=topics");
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

                // Kiểm tra xem đề tài có được tạo bởi sinh viên mà giảng viên này đang hướng dẫn không
                $stmt3 = $db->prepare(
                    "SELECT COUNT(*) 
                     FROM topics t
                     JOIN users u ON t.created_by = u.id
                     JOIN students s ON s.user_id = u.id
                     JOIN supervision_assignments sa ON sa.student_id = s.id
                     WHERE t.id = ? AND sa.lecturer_id = ?"
                );
                $stmt3->execute([$id, $lecturerId]);
                $isCreatedBySupervisedStudent = (int)$stmt3->fetchColumn() > 0;

                if (!$isAssigned && !$isDesired && !$isCreatedBySupervisedStudent) {
                    $_SESSION['error'] = "Bạn không có quyền duyệt đề tài này.";
                    header("Location: index.php?page=topic-management&tab=topics");
                    exit();
                }
            }

            $this->topicModel->updateStatus($id, $status);
            
            $topic = $this->topicModel->find($id);
            if ($topic && !empty($topic['created_by'])) {
                $statusText = $status === 'approved' ? 'đã được DUYỆT' : ($status === 'rejected' ? 'đã bị TỪ CHỐI' : 'được chuyển về CHỜ DUYỆT');
                require_once '../app/models/Notification.php';
                $notifModel = new Notification();
                $notifModel->create([
                    'user_id' => $topic['created_by'],
                    'content' => "Đề tài đề xuất \"{$topic['title']}\" của bạn $statusText.",
                    'type'    => 'info'
                ]);
            }

            LogService::log('update_topic_status', "Updated status of topic ID: $id to $status");
            $_SESSION['success'] = "Cập nhật trạng thái thành công.";
        }
        header("Location: index.php?page=topic-management&tab=topics");
        exit();
    }
}
