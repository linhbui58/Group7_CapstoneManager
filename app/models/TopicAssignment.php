<?php
class TopicAssignment {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $sql = "SELECT ta.*, t.title as topic_title, l.full_name as lecturer_name 
                FROM topic_assignments ta
                JOIN topics t ON ta.topic_id = t.id
                JOIN lecturers l ON ta.lecturer_id = l.id
                ORDER BY ta.id DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO topic_assignments (topic_id, lecturer_id, assigned_at) VALUES (?, ?, ?)");
            return $stmt->execute([
                $data['topic_id'],
                $data['lecturer_id'],
                date('Y-m-d H:i:s')
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM topic_assignments WHERE id = ?");
        return $stmt->execute([$id]);
    }
}