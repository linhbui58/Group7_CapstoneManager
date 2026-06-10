<?php

class Notification {

    private $conn;

    public function __construct(){

        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAll(){

        $sql = "SELECT notifications.*,
                       users.email
                FROM notifications
                JOIN users
                ON users.id = notifications.user_id
                ORDER BY notifications.id DESC";

        return $this->conn
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data){

        $sql = "INSERT INTO notifications
                (user_id,content,type,is_read)
                VALUES(?,?,?,0)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $data['user_id'],
            $data['content'],
            $data['type']
        ]);
    }

    public function markRead($id){

        $stmt = $this->conn->prepare(
            "UPDATE notifications
             SET is_read=1
             WHERE id=?"
        );

        return $stmt->execute([$id]);
    }

    public function delete($id){

        $stmt = $this->conn->prepare(
            "DELETE FROM notifications WHERE id=?"
        );

        return $stmt->execute([$id]);
    }

    public function getByUser($userId){

        $stmt = $this->conn->prepare(
            "SELECT notifications.*, users.email
             FROM notifications
             JOIN users ON users.id = notifications.user_id
             WHERE notifications.user_id = ?
             ORDER BY notifications.id DESC"
        );

        $stmt->execute([$userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countUnread($userId){

        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) AS total
             FROM notifications
             WHERE user_id = ? AND is_read = 0"
        );

        $stmt->execute([$userId]);

        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function markAllRead($userId){

        $stmt = $this->conn->prepare(
            "UPDATE notifications SET is_read = 1 WHERE user_id = ?"
        );

        return $stmt->execute([$userId]);
    }
}