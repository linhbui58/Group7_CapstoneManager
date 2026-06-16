<?php

class WorkloadService {
    public static function getLecturerWorkload($lecturerId) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT total_students FROM lecturer_workload WHERE id = ?");
        $stmt->execute([$lecturerId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['total_students'] : 0;
    }

    public static function canAssign($lecturerId) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT quota FROM lecturers WHERE id = ?");
        $stmt->execute([$lecturerId]);
        $lecturer = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$lecturer) return false;

        $quota = (int)$lecturer['quota'];
        $currentWorkload = self::getLecturerWorkload($lecturerId);

        return $currentWorkload < $quota;
    }
}
