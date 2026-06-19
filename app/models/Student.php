<?php

class Student {

    private $conn;

    public function __construct(){

        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll(){

        $sql = "SELECT students.*, users.email
                FROM students
                JOIN users
                ON users.id = students.user_id
                ORDER BY students.id DESC";

        return $this->conn
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id){

        $stmt = $this->conn->prepare(
            "SELECT students.*, users.email
             FROM students
             JOIN users ON users.id = students.user_id
             WHERE students.id = ?"
        );

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByUserId($userId){

        $stmt = $this->conn->prepare(
            "SELECT * FROM students WHERE user_id=?"
        );

        $stmt->execute([$userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data){

        $stmt = $this->conn->prepare(
            "INSERT INTO students
            (user_id,full_name,faculty)
            VALUES(?,?,?)"
        );

        $stmt->execute([
            $data['user_id'],
            $data['full_name'],
            $data['faculty'] ?? ''
        ]);

        return $this->conn->lastInsertId();
    }

    /**
     * Admin tạo student kèm tài khoản user (email + password).
     */
    public function createWithUser($data) {
        try {
            $this->conn->beginTransaction();

            $stmtUser = $this->conn->prepare(
                "INSERT INTO users (email, password, role, status) VALUES (?, ?, 'student', 'active')"
            );
            $stmtUser->execute([
                $data['email'],
                password_hash($data['password'] ?: '123456', PASSWORD_DEFAULT),
            ]);
            $userId = (int)$this->conn->lastInsertId();

            $stmtStu = $this->conn->prepare(
                "INSERT INTO students (user_id, full_name, faculty) VALUES (?, ?, ?)"
            );
            $stmtStu->execute([
                $userId,
                $data['full_name'],
                $data['faculty'] ?? ''
            ]);

            $this->conn->commit();
            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function update($id, $data){
        $stmt = $this->conn->prepare(
            "UPDATE students
             SET full_name=?, faculty=?
             WHERE id=?"
        );

        return $stmt->execute([
            $data['full_name'],
            $data['faculty'] ?? '',
            $id
        ]);
    }

    public function delete($id){

        $stmt = $this->conn->prepare(
            "DELETE FROM students WHERE id=?"
        );

        return $stmt->execute([$id]);
    }
}