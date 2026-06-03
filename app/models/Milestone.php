<?php
class Milestone {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $sql = "SELECT m.*, s.name as semester_name 
                FROM milestones m 
                LEFT JOIN semesters s ON m.semester_id = s.id 
                ORDER BY m.deadline ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->conn->prepare("SELECT * FROM milestones WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        // datetime-local input trả về "2026-05-13T10:00", MySQL cần "2026-05-13 10:00:00"
        $deadline = isset($data['deadline'])
            ? str_replace('T', ' ', $data['deadline'])
            : null;
        $stmt = $this->conn->prepare("INSERT INTO milestones (title, deadline, semester_id) VALUES (?, ?, ?)");
        return $stmt->execute([$data['title'], $deadline, $data['semester_id']]);
    }

    public function update($id, $data) {
        $deadline = isset($data['deadline'])
            ? str_replace('T', ' ', $data['deadline'])
            : null;
        $stmt = $this->conn->prepare("UPDATE milestones SET title = ?, deadline = ?, semester_id = ? WHERE id = ?");
        return $stmt->execute([$data['title'], $deadline, $data['semester_id'], $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM milestones WHERE id = ?");
        return $stmt->execute([$id]);
    }
}