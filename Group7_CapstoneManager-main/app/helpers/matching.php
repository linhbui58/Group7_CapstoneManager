<?php

class MatchingHelper {

    public static function suggestLecturers($keywords){

        $db = Database::getInstance()-> getConnection();

        $sql = "SELECT *
                FROM lecturers
                WHERE expertise LIKE ?";

        $stmt = $db->prepare($sql);

        $stmt->execute([
            "%" . $keywords . "%"
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}