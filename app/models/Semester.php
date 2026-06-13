<?php
class Semester {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $sql = "SELECT * FROM semesters ORDER BY start_date DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->conn->prepare("SELECT * FROM semesters WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO semesters (name, start_date, end_date) VALUES (?, ?, ?)");
        return $stmt->execute([
            $data['name'],
            $data['start_date'],
            $data['end_date']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE semesters SET name = ?, start_date = ?, end_date = ? WHERE id = ?");
        return $stmt->execute([
            $data['name'],
            $data['start_date'],
            $data['end_date'],
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM semesters WHERE id = ?");
        return $stmt->execute([$id]);
    }
}