<?php
/**
 * TopicManagementController
 * 
 * Gộp Topics + Registrations vào 1 trang với tab switching
 */
class TopicManagementController {

    private $topicModel;
    private $regModel;
    private $semesterModel;
    private $lecturerModel;

    public function __construct() {
        AuthMiddleware::check();
        $this->topicModel    = new Topic();
        $this->regModel      = new TopicRegistration();
        $this->semesterModel = new Semester();
        $this->lecturerModel = new Lecturer();
    }

    /**
     * Trang chính: hiển thị cả Topics và Registrations trong 1 view với tabs
     */
    public function index() {
        $role = $_SESSION['user']['role'] ?? '';

        // ── TOPICS DATA ──
        $search     = trim($_GET['search']      ?? '');
        $filterSem  = (int)($_GET['semester_id'] ?? 0);
        $filterStat = trim($_GET['status']       ?? '');

        if ($role === 'admin') {
            $topics = $this->topicModel->search($search, $filterSem, $filterStat);
        } elseif ($role === 'lecturer') {
            $lecturerId = $_SESSION['user']['lecturer_id'] ?? null;
            if (!$lecturerId) {
                $lecturerModel = new Lecturer();
                $lecturer = $lecturerModel->findByUserId($_SESSION['user']['id']);
                if ($lecturer) {
                    $_SESSION['user']['lecturer_id'] = $lecturer['id'];
                    $lecturerId = $lecturer['id'];
                }
            }
            $topics = $this->topicModel->getByLecturer($lecturerId, $search, $filterSem, $filterStat);
        } else {
            // student: chỉ xem approved
            $topics = $this->topicModel->search($search, $filterSem, 'approved');
        }

        // ── REGISTRATIONS DATA ──
        if ($role === 'admin') {
            $registrations = $this->regModel->getAll();
        } elseif ($role === 'lecturer') {
            $lecturerId = $_SESSION['user']['lecturer_id'] ?? null;
            if (!$lecturerId) {
                $lecturerModel = new Lecturer();
                $lecturer = $lecturerModel->findByUserId($_SESSION['user']['id']);
                if ($lecturer) {
                    $_SESSION['user']['lecturer_id'] = $lecturer['id'];
                    $lecturerId = $lecturer['id'];
                }
            }
            $registrations = $lecturerId ? $this->regModel->getByLecturer($lecturerId) : [];
        } elseif ($role === 'student') {
            $studentId = $_SESSION['user']['student_id'] ?? null;
            if (!$studentId) {
                $studentModel = new Student();
                $student = $studentModel->findByUserId($_SESSION['user']['id']);
                if ($student) {
                    $_SESSION['user']['student_id'] = $student['id'];
                    $studentId = $student['id'];
                }
            }
            $registrations = $studentId ? $this->regModel->getByStudent($studentId) : [];
        } else {
            $registrations = [];
        }

        // ── COMMON DATA ──
        $semesters = $this->semesterModel->getAll();

        // ── RENDER VIEW ──
        require '../app/views/topic-management/index.php';
    }
}
