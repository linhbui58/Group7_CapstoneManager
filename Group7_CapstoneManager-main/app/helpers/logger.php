<?php

class Logger {

    public static function log($userId, $action){

        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare(
            "INSERT INTO system_logs(user_id, action)
             VALUES(?, ?)"
        );

        $stmt->execute([
            $userId,
            $action
        ]);
    }
}