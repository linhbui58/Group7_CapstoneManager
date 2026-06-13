<?php

class SystemLog {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    /**
     * Lấy toàn bộ nhật ký, join với users để lấy email và role.
     */
    public function getAll() {
        $sql = "SELECT l.*, u.email, u.role 
                FROM system_logs l 
                LEFT JOIN users u ON l.user_id = u.id 
                ORDER BY l.created_at DESC";
        try {
            $stmt = $this->conn->query($sql);
            return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Ghi log mới vào bảng system_logs.
     */
    public function create($data) {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO system_logs (user_id, action, details) VALUES (?, ?, ?)"
            );
            return $stmt->execute([
                $data['user_id']  ?? null,
                $data['action']   ?? 'UNKNOWN_ACTION',
                $data['details']  ?? null,
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
