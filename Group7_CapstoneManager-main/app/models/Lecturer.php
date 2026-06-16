<?php

class Lecturer {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $sql = "SELECT lecturers.*, users.email 
                FROM lecturers 
                JOIN users ON users.id = lecturers.user_id 
                ORDER BY lecturers.id DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->conn->prepare("
            SELECT lecturers.*, users.email 
            FROM lecturers 
            JOIN users ON users.id = lecturers.user_id 
            WHERE lecturers.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByUserId($userId) {
        $stmt = $this->conn->prepare("
            SELECT lecturers.*, users.email 
            FROM lecturers 
            JOIN users ON users.id = lecturers.user_id 
            WHERE lecturers.user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Tạo giảng viên.
     *
     * Nếu $data['user_id'] đã có (gọi từ AuthController sau khi tạo user rồi)
     * → chỉ INSERT vào bảng lecturers.
     *
     * Nếu không có user_id (gọi từ Admin panel)
     * → tạo user mới với email + mật khẩu mặc định 123456, rồi INSERT lecturers.
     */
    public function create($data) {
        try {
            $this->conn->beginTransaction();

            if (!empty($data['user_id'])) {
                // Gọi từ AuthController — user đã tồn tại
                $userId = (int)$data['user_id'];
            } else {
                // Gọi từ Admin panel — tạo user mới
                $stmtUser = $this->conn->prepare(
                    "INSERT INTO users (email, password, role, status) VALUES (?, ?, 'lecturer', 'active')"
                );
                $stmtUser->execute([
                    $data['email'],
                    password_hash('123456', PASSWORD_DEFAULT),
                ]);
                $userId = (int)$this->conn->lastInsertId();
            }

            $stmtLec = $this->conn->prepare(
                "INSERT INTO lecturers (user_id, full_name, expertise) VALUES (?, ?, ?)"
            );
            $stmtLec->execute([
                $userId,
                $data['full_name'],
                $data['expertise'] ?? '',
            ]);

            $this->conn->commit();
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function update($id, $data) {
        $stmt = $this->conn->prepare(
            "UPDATE lecturers SET full_name = ?, expertise = ? WHERE id = ?"
        );
        return $stmt->execute([$data['full_name'], $data['expertise'] ?? '', $id]);
    }

    public function delete($id) {
        $lec = $this->find($id);
        if (!$lec) return false;
        // ON DELETE CASCADE sẽ xóa lecturers khi xóa users
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$lec['user_id']]);
    }
}
