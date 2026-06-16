<?php

class QuotaHelper {

    public static function lecturerCount($lecturerId){

        $db = Database::getInstance()->getConnection();

        $sql = "SELECT COUNT(*) total
                FROM topic_assignments ta
                JOIN topic_registrations tr
                ON ta.topic_id = tr.topic_id
                WHERE ta.lecturer_id = ?
                AND tr.status = 'approved'";

        $stmt = $db->prepare($sql);

        $stmt->execute([$lecturerId]);

        return $stmt->fetch()['total'];
    }

    public static function isFull($lecturerId){

        return self::lecturerCount($lecturerId) >= 8;
    }
}