<?php
class RegistrationController {

    private $regModel;

    public function __construct() {
        AuthMiddleware::check();
        $this->regModel = new TopicRegistration();
    }

    /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
     | LIST
     | Admin / Lecturer : xem táº¥t cáº£
     | Student          : chá»‰ xem cá»§a mÃ¬nh
     â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
    public function index() {
        $role = $_SESSION['user']['role'] ?? '';

        if ($role === 'admin') {
            $registrations = $this->regModel->getAll();
        } elseif ($role === 'lecturer') {
            // Lecturer chá»‰ tháº¥y Ä‘Äƒng kÃ½ cá»§a sinh viÃªn chá»n mÃ¬nh
            $lecturerId = $_SESSION['user']['lecturer_id'] ?? null;

            // Náº¿u session chÆ°a cÃ³ lecturer_id, thá»­ láº¥y láº¡i tá»« DB
            if (!$lecturerId) {
                $lecturerModel = new Lecturer();
                $lecturer = $lecturerModel->findByUserId($_SESSION['user']['id']);
                if ($lecturer) {
                    $_SESSION['user']['lecturer_id'] = $lecturer['id'];
                    $lecturerId = $lecturer['id'];
                }
            }

            $registrations = $lecturerId
                ? $this->regModel->getByLecturer($lecturerId)
                : [];
        } elseif ($role === 'student') {
            $studentId = $_SESSION['user']['student_id'] ?? null;

            // Náº¿u session chÆ°a cÃ³ student_id, thá»­ láº¥y láº¡i tá»« DB
            if (!$studentId) {
                $studentModel = new Student();
                $student = $studentModel->findByUserId($_SESSION['user']['id']);
                if ($student) {
                    $_SESSION['user']['student_id'] = $student['id'];
                    $studentId = $student['id'];
                }
            }

            $registrations = $studentId
                ? $this->regModel->getByStudent($studentId)
                : [];
        } else {
            $registrations = [];
        }

        require '../app/views/registrations/index.php';
    }

    /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
     | CREATE FORM  (student only)
     â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
    public function create() {
        if ($_SESSION['user']['role'] !== 'student') {
            header("Location: index.php?page=registrations");
            exit();
        }

        $topicModel    = new Topic();
        $semesterModel = new Semester();
        $lecturerModel = new Lecturer();

        $topics    = $topicModel->getAvailable();   // chá»‰ topic Ä‘ang má»Ÿ
        $semesters = $semesterModel->getAll();

        // Láº¥y thÃ´ng tin student hiá»‡n táº¡i
        $studentModel = new Student();
        $student = $studentModel->findByUserId($_SESSION['user']['id']);

        if ($student && !empty($student['faculty'])) {
            $lecturers = $lecturerModel->getByFaculty($student['faculty']);
        } else {
            $lecturers = $lecturerModel->getAll();
        }

        require '../app/views/registrations/create.php';
    }

    /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
     | STORE  (student only)
     | Constraint: khÃ´ng Ä‘Äƒng kÃ½ > 1 Ä‘á» tÃ i / há»c ká»³
     â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
    public function store() {
        if ($_SESSION['user']['role'] !== 'student') {
            header("Location: index.php?page=registrations");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            verifyCSRF();
            header("Location: index.php?page=registration-create");
            exit();
        verifyCSRF();
        }

        $studentId  = $_SESSION['user']['student_id'] ?? null;

        // Fallback: láº¥y student_id tá»« DB náº¿u session chÆ°a cÃ³
        if (!$studentId) {
            $studentModel = new Student();
            $student = $studentModel->findByUserId($_SESSION['user']['id']);
            if ($student) {
                $_SESSION['user']['student_id'] = $student['id'];
                $studentId = $student['id'];
            }
        }

        $topicId    = (int)($_POST['topic_id']    ?? 0);
        $semesterId = (int)($_POST['semester_id'] ?? 0);
        $desiredLec = $_POST['desired_lecturer_id'] ? (int)$_POST['desired_lecturer_id'] : null;
        $keywords   = trim($_POST['keywords'] ?? '');

        if (!$studentId || !$topicId || !$semesterId) {
            $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin.";
            header("Location: index.php?page=registration-create");
            exit();
        }

        // Kiểm tra deadline đăng ký đề tài (dựa vào end_date của semester)
        $semesterModel = new Semester();
        $semester = $semesterModel->find($semesterId);
        if ($semester && time() > strtotime($semester['end_date'] . ' 23:59:59')) {
            $_SESSION['error'] = "Đã hết hạn đăng ký đề tài cho học kỳ này.";
            header("Location: index.php?page=registration-create");
            exit();
        }

        // Constraint: 1 đề tài / học kỳ
        if ($this->regModel->hasAlreadyRegistered($studentId, $semesterId)) {
            $_SESSION['error'] = "Báº¡n Ä‘Ã£ Ä‘Äƒng kÃ½ Ä‘á» tÃ i trong há»c ká»³ nÃ y rá»“i.";
            header("Location: index.php?page=registration-create");
            exit();
        }

        $this->regModel->create([
            'student_id'          => $studentId,
            'topic_id'            => $topicId,
            'semester_id'         => $semesterId,
            'desired_lecturer_id' => $desiredLec,
            'keywords'            => $keywords,
        ]);

        LogService::log('register_topic', "Student ID: $studentId registered Topic ID: $topicId");

        $_SESSION['success'] = "ÄÄƒng kÃ½ Ä‘á» tÃ i thÃ nh cÃ´ng. Vui lÃ²ng chá» duyá»‡t.";
        header("Location: index.php?page=topic-management&tab=registrations");
        exit();
    }

    /* â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
     | UPDATE STATUS  (admin / lecturer only)
     â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
    public function updateStatus() {
        $role = $_SESSION['user']['role'];

        if (!in_array($role, ['admin', 'lecturer'])) {
            // Student cá»‘ tÃ¬nh gá»i URL nÃ y â†’ cháº·n cá»©ng
            http_response_code(403);
            $_SESSION['error'] = "Báº¡n khÃ´ng cÃ³ quyá»n thá»±c hiá»‡n thao tÃ¡c nÃ y.";
            header("Location: index.php?page=registrations");
            exit();
        }

        $id     = (int)($_GET['id']     ?? 0);
        $status = $_GET['status'] ?? '';

        // Whitelist status
        if (!in_array($status, ['approved', 'rejected', 'pending'])) {
            header("Location: index.php?page=registrations");
            exit();
        }

        if ($id) {
            // Lecturer chá»‰ Ä‘Æ°á»£c duyá»‡t Ä‘Äƒng kÃ½ chá»n mÃ¬nh HOáº¶C thuá»™c topic Ä‘Æ°á»£c assign cho mÃ¬nh
            if ($role === 'lecturer') {
                $lecturerId = $_SESSION['user']['lecturer_id'] ?? null;
                $reg = $this->regModel->find($id);
                if (!$reg) {
                    $_SESSION['error'] = "KhÃ´ng tÃ¬m tháº¥y Ä‘Äƒng kÃ½.";
                    header("Location: index.php?page=registrations");
                    exit();
                }
                $isDesired  = (int)($reg['desired_lecturer_id'] ?? 0) === (int)$lecturerId;
                // Kiá»ƒm tra topic cÃ³ Ä‘Æ°á»£c assign cho lecturer khÃ´ng
                $db   = Database::getInstance()->getConnection();
                $stmt = $db->prepare(
                    "SELECT COUNT(*) FROM topic_assignments WHERE topic_id = ? AND lecturer_id = ?"
                );
                $stmt->execute([$reg['topic_id'], $lecturerId]);
                $isAssigned = (int)$stmt->fetchColumn() > 0;

                if (!$isDesired && !$isAssigned) {
                    $_SESSION['error'] = "Báº¡n khÃ´ng cÃ³ quyá»n duyá»‡t Ä‘Äƒng kÃ½ nÃ y.";
                    header("Location: index.php?page=registrations");
                    exit();
                }
            }
            $this->regModel->updateStatus($id, $status);
            LogService::log('update_registration_status', "Updated registration ID: $id status to $status");
            $_SESSION['success'] = "Cáº­p nháº­t tráº¡ng thÃ¡i thÃ nh cÃ´ng.";
        }

        header("Location: index.php?page=registrations");
        exit();
    }
}
