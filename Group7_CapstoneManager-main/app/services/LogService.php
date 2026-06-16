<?php

class LogService {
    public static function log($action, $description, $status = 'success') {
        $db = Database::getInstance()->getConnection();
        $userId = $_SESSION['user']['id'] ?? null;
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';

        $stmt = $db->prepare(
            "INSERT INTO system_logs (user_id, action, description, ip_address, user_agent, status)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([$userId, $action, $description, $ipAddress, $userAgent, $status]);
    }
}
