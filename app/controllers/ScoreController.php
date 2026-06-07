<?php

class ScoreController {

    private $scoreModel;

    public function __construct() {
        AuthMiddleware::check();
        $this->scoreModel = new Score();
    }

    public function index() {
        $role = $_SESSION['user']['role'];

        if ($role === 'lecturer') {
            $lecturerId = $_SESSION['user']['lecturer_id'] ?? null;
            // Chỉ xem điểm của các submission thuộc đề tài mình phụ trách
            $db  = Database::getInstance()->getConnection();
            $sql = "SELECT es.*,
                           s.full_name  AS student_name,
                           m.title      AS topic_title,
                           l.full_name  AS lecturer_name
                    FROM evaluation_scores es
                    JOIN submissions sub ON es.submission_id = sub.id
                    JOIN students    s   ON sub.student_id   = s.id
                    JOIN milestones  m   ON sub.milestone_id = m.id
                    JOIN lecturers   l   ON es.lecturer_id   = l.id
                    WHERE es.lecturer_id = ?
                    ORDER BY es.graded_at DESC";
            $stmt = $db->prepare($sql);
            $stmt->execute([$lecturerId]);
            $scores = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $scores = $this->scoreModel->getAll();
        }

        require '../app/views/scores/index.php';
    }

    public function create() {
        if ($_SESSION['user']['role'] === 'student') {
            redirect('scores');
        }

        $role       = $_SESSION['user']['role'];
        $lecturerId = $_SESSION['user']['lecturer_id'] ?? null;
        $db         = Database::getInstance()->getConnection();

        if ($role === 'lecturer' && $lecturerId) {
            // Chỉ hiện submissions của sinh viên mình phụ trách
            $sql = "SELECT sub.id, s.full_name AS student_name, m.title AS topic_title
                    FROM submissions sub
                    JOIN students   s ON sub.student_id   = s.id
                    JOIN milestones m ON sub.milestone_id = m.id
                    WHERE (
                        (sub.topic_id IS NOT NULL AND sub.topic_id IN (
                            SELECT topic_id FROM topic_assignments WHERE lecturer_id = ?
                        ))
                        OR sub.student_id IN (
                            SELECT student_id FROM topic_registrations WHERE desired_lecturer_id = ?
                        )
                        OR sub.student_id IN (
                            SELECT tr.student_id FROM topic_registrations tr
                            JOIN topic_assignments ta ON ta.topic_id = tr.topic_id
                            WHERE ta.lecturer_id = ?
                        )
                    )
                    ORDER BY sub.id DESC";
            $stmt = $db->prepare($sql);
            $stmt->execute([$lecturerId, $lecturerId, $lecturerId]);
        } else {
            // Admin thấy tất cả
            $sql = "SELECT sub.id, s.full_name AS student_name, m.title AS topic_title
                    FROM submissions sub
                    JOIN students  s ON sub.student_id   = s.id
                    JOIN milestones m ON sub.milestone_id = m.id
                    ORDER BY sub.id DESC";
            $stmt = $db->query($sql);
        }
        $submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require '../app/views/scores/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('scores');
        }

        $lecturerId = $_SESSION['user']['lecturer_id'] ?? null;

        if ($_SESSION['user']['role'] === 'admin' && !empty($_POST['lecturer_id'])) {
            $lecturerId = (int)$_POST['lecturer_id'];
        }

        if (!$lecturerId) {
            $_SESSION['error'] = "Không xác định được giảng viên chấm điểm.";
            redirect('score-create');
        }

        $submissionId = (int)($_POST['submission_id'] ?? 0);

        // Lecturer chỉ được chấm submission thuộc đề tài mình
        if ($_SESSION['user']['role'] === 'lecturer') {
            $subModel = new Submission();
            if (!$subModel->belongsToLecturer($submissionId, $lecturerId)) {
                $_SESSION['error'] = "Bạn không có quyền chấm điểm bài nộp này.";
                redirect('score-create');
            }
        }

        $this->scoreModel->create([
            'submission_id' => $submissionId,
            'lecturer_id'   => (int)$lecturerId,
            'score'         => $_POST['score'] ?? 0,
            'feedback'      => trim($_POST['feedback'] ?? ''),
        ]);

        // Gửi notification cho sinh viên
        $db   = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            "SELECT s.user_id, m.title AS milestone_title
             FROM submissions sub
             JOIN students   s ON s.id  = sub.student_id
             JOIN milestones m ON m.id  = sub.milestone_id
             WHERE sub.id = ?"
        );
        $stmt->execute([$submissionId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $notifModel = new Notification();
            $notifModel->create([
                'user_id' => $row['user_id'],
                'content' => "Bài nộp cột mốc \"{$row['milestone_title']}\" của bạn đã được chấm điểm.",
                'type'    => 'score',
            ]);
        }

        $_SESSION['success'] = "Lưu điểm thành công.";
        redirect('scores');
    }

    public function edit() {
        $id    = (int)($_GET['id'] ?? 0);
        $score = $this->scoreModel->find($id);
        if (!$score) {
            $_SESSION['error'] = "Không tìm thấy điểm số.";
            redirect('scores');
        }

        // Lecturer chỉ được sửa điểm do mình chấm
        if ($_SESSION['user']['role'] === 'lecturer') {
            $lecturerId = $_SESSION['user']['lecturer_id'] ?? null;
            if ((int)$score['lecturer_id'] !== (int)$lecturerId) {
                $_SESSION['error'] = "Bạn không có quyền sửa điểm này.";
                redirect('scores');
            }
        }

        $db  = Database::getInstance()->getConnection();
        $sql = "SELECT sub.id, s.full_name AS student_name, m.title AS topic_title
                FROM submissions sub
                JOIN students   s ON sub.student_id   = s.id
                JOIN milestones m ON sub.milestone_id = m.id
                ORDER BY sub.id DESC";
        $submissions = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        require '../app/views/scores/edit.php';
    }

    public function update() {
        $id = (int)($_GET['id'] ?? 0);

        // Lecturer chỉ được cập nhật điểm do mình chấm
        if ($_SESSION['user']['role'] === 'lecturer' && $id) {
            $score      = $this->scoreModel->find($id);
            $lecturerId = $_SESSION['user']['lecturer_id'] ?? null;
            if (!$score || (int)$score['lecturer_id'] !== (int)$lecturerId) {
                $_SESSION['error'] = "Bạn không có quyền cập nhật điểm này.";
                redirect('scores');
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $this->scoreModel->update($id, [
                'score'    => $_POST['score']    ?? 0,
                'feedback' => trim($_POST['feedback'] ?? ''),
            ]);
            $_SESSION['success'] = "Cập nhật điểm thành công.";
        }
        redirect('scores');
    }

    public function delete() {
        $id = (int)($_GET['id'] ?? 0);

        // Lecturer chỉ được xóa điểm do mình chấm
        if ($_SESSION['user']['role'] === 'lecturer' && $id) {
            $score      = $this->scoreModel->find($id);
            $lecturerId = $_SESSION['user']['lecturer_id'] ?? null;
            if (!$score || (int)$score['lecturer_id'] !== (int)$lecturerId) {
                $_SESSION['error'] = "Bạn không có quyền xóa điểm này.";
                redirect('scores');
            }
        }

        if ($id) {
            $this->scoreModel->delete($id);
            $_SESSION['success'] = "Đã xóa điểm.";
        }
        redirect('scores');
    }
}
