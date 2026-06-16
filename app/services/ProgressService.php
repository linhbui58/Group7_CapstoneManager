<?php

class ProgressService {
    public static function getStudentProgress($studentId) {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("SELECT * FROM student_progress WHERE id = ?");
        $stmt->execute([$studentId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $totalSubmissions = $row ? (int)$row['total_submissions'] : 0;
        $averageScore = $row ? (float)$row['average_score'] : 0;
        
        // Calculate percentage based on total milestones
        $stmtMs = $db->query("SELECT COUNT(*) FROM milestones");
        $totalMilestones = (int)$stmtMs->fetchColumn();

        $percentage = 0;
        if ($totalMilestones > 0) {
            $percentage = min(100, round(($totalSubmissions / $totalMilestones) * 100));
        }

        return [
            'total_submissions' => $totalSubmissions,
            'average_score' => $averageScore,
            'progress_percentage' => $percentage,
            'total_milestones' => $totalMilestones
        ];
    }
}
