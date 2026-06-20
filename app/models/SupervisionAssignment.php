<?php
class SupervisionAssignment {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $sql = "SELECT sa.*, 
                       s.full_name as student_name, s.id as student_code, s.faculty as student_faculty,
                       l.full_name as lecturer_name, l.id as lecturer_code,
                       sem.name as semester_name,
                       u.email as assigned_by_name
                FROM supervision_assignments sa
                JOIN students s ON sa.student_id = s.id
                JOIN lecturers l ON sa.lecturer_id = l.id
                JOIN semesters sem ON sa.semester_id = sem.id
                LEFT JOIN users u ON sa.assigned_by = u.id
                ORDER BY sa.assigned_at DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->conn->prepare(
            "INSERT INTO supervision_assignments (student_id, lecturer_id, semester_id, assigned_by) 
             VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([
            $data['student_id'],
            $data['lecturer_id'],
            $data['semester_id'],
            $data['assigned_by']
        ]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM supervision_assignments WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function findByStudentAndSemester($studentId, $semesterId) {
        $stmt = $this->conn->prepare("SELECT * FROM supervision_assignments WHERE student_id = ? AND semester_id = ?");
        $stmt->execute([$studentId, $semesterId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->conn->prepare("SELECT * FROM supervision_assignments WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
