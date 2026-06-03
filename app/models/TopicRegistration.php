<?php
class TopicRegistration {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    // Admin/Lecturer: Xem toàn bộ kèm tên sinh viên
    public function getAll() {
        $sql = "SELECT r.*, t.title as topic_title, s.full_name as student_name, 
                       sem.name as semester_name, l.full_name as lecturer_name
                FROM topic_registrations r
                JOIN topics t ON r.topic_id = t.id
                JOIN students s ON r.student_id = s.id
                JOIN semesters sem ON r.semester_id = sem.id
                LEFT JOIN lecturers l ON r.desired_lecturer_id = l.id
                ORDER BY r.created_at DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Student: Chỉ xem bài của mình
    public function getByStudent($studentId) {
        $sql = "SELECT r.*, t.title as topic_title, sem.name as semester_name, l.full_name as lecturer_name
                FROM topic_registrations r
                JOIN topics t ON r.topic_id = t.id
                JOIN semesters sem ON r.semester_id = sem.id
                LEFT JOIN lecturers l ON r.desired_lecturer_id = l.id
                WHERE r.student_id = ? ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE topic_registrations SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function hasAlreadyRegistered($studentId, $semesterId) {
        $stmt = $this->conn->prepare("SELECT id FROM topic_registrations WHERE student_id = ? AND semester_id = ?");
        $stmt->execute([$studentId, $semesterId]);
        return $stmt->fetch() ? true : false;
    }

    // Tìm 1 bản ghi theo id
    public function find($id) {
        $stmt = $this->conn->prepare("SELECT * FROM topic_registrations WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo đăng ký mới
    public function create($data) {
        $stmt = $this->conn->prepare(
            "INSERT INTO topic_registrations 
             (student_id, topic_id, semester_id, desired_lecturer_id, keywords, status, created_at)
             VALUES (?, ?, ?, ?, ?, 'pending', NOW())"
        );
        return $stmt->execute([
            $data['student_id'],
            $data['topic_id'],
            $data['semester_id'],
            $data['desired_lecturer_id'],
            $data['keywords'],
        ]);
    }

    // Lecturer: xem đăng ký của sinh viên chọn mình HOẶC thuộc topic được assign cho mình
    public function getByLecturer($lecturerId) {
        $sql = "SELECT DISTINCT r.*, t.title as topic_title, s.full_name as student_name,
                       sem.name as semester_name, l.full_name as lecturer_name
                FROM topic_registrations r
                JOIN topics t ON r.topic_id = t.id
                JOIN students s ON r.student_id = s.id
                JOIN semesters sem ON r.semester_id = sem.id
                LEFT JOIN lecturers l ON r.desired_lecturer_id = l.id
                WHERE r.desired_lecturer_id = ?
                   OR r.topic_id IN (
                       SELECT topic_id FROM topic_assignments WHERE lecturer_id = ?
                   )
                ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$lecturerId, $lecturerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}