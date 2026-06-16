<?php

class NotificationHelper {

    public static function send($userId, $content, $type = 'system'){

        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "INSERT INTO notifications
            (user_id, content, type)
            VALUES (?, ?, ?)"
        );

        return $stmt->execute([
            $userId,
            $content,
            $type
        ]);
    }
}