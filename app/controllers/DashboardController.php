<?php

class DashboardController {

    public function index() {
        AuthMiddleware::check();

        $topicModel      = new Topic();
        $studentModel    = new Student();
        $lecturerModel   = new Lecturer();
        $submissionModel = new Submission();

        // Stat counts
        $allTopics        = $topicModel->getAll();
        $allSubmissions   = $submissionModel->getAll();

        $totalTopics      = count($allTopics);
        $totalStudents    = count($studentModel->getAll());
        $totalLecturers   = count($lecturerModel->getAll());
        $totalSubmissions = count($allSubmissions);

        // Topic status breakdown
        $draftCount    = 0;
        $pendingCount  = 0;
        $approvedCount = 0;
        $rejectedCount = 0;
        foreach ($allTopics as $t) {
            switch ($t['status'] ?? '') {
                case 'draft':    $draftCount++;    break;
                case 'pending':  $pendingCount++;  break;
                case 'approved': $approvedCount++; break;
                case 'rejected': $rejectedCount++; break;
            }
        }

        // Recent topics (last 5)
        $recentTopics = array_slice($allTopics, 0, 5);

        // Recent submissions (last 5) — getAll() đã có student_name
        $recentSubmissions = array_slice($allSubmissions, 0, 5);

        require '../app/views/dashboard/index.php';
    }
}
