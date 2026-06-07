<?php

class Score {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    /**
     * Lấy tất cả điểm số kèm tên sinh viên, tên cột mốc và tên giảng viên.
     * submissions không có topic_id nên join qua milestones.
     */
    public function getAll() {
        $sql = "SELECT es.*,
                       s.full_name  AS student_name,
                       m.title      AS topic_title,
                       l.full_name  AS lecturer_name
                FROM evaluation_scores es
                JOIN submissions sub ON es.submission_id = sub.id
                JOIN students    s   ON sub.student_id   = s.id
                JOIN milestones  m   ON sub.milestone_id = m.id
                JOIN lecturers   l   ON es.lecturer_id   = l.id
                ORDER BY es.graded_at DESC";
        try {
            return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function find($id) {
        $stmt = $this->conn->prepare("SELECT * FROM evaluation_scores WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->conn->prepare(
            "INSERT INTO evaluation_scores (submission_id, lecturer_id, score, feedback) VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([
            $data['submission_id'],
            $data['lecturer_id'],
            $data['score'],
            $data['feedback'] ?? null,
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->conn->prepare(
            "UPDATE evaluation_scores SET score = ?, feedback = ? WHERE id = ?"
        );
        return $stmt->execute([
            $data['score'],
            $data['feedback'] ?? null,
            $id,
        ]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM evaluation_scores WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
