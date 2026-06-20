<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

set_exception_handler(function($e) {
    error_log($e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
    if (function_exists('abort')) {
        abort(500, "An unexpected error occurred. Please try again later.");
    } else {
        http_response_code(500);
        echo "<h1>500 Internal Server Error</h1>";
        exit;
    }
});

// 1. Config (session_start, BASE_URL, etc.) — phải đầu tiên
require_once '../config/config.php';
require_once '../core/Database.php';

// 2. Helpers
require_once '../app/helpers/redirect.php';
require_once '../app/helpers/response.php';
require_once '../app/helpers/auth.php';
require_once '../app/helpers/csrf.php';
require_once '../app/helpers/logger.php';
require_once '../app/helpers/notification.php';
require_once '../app/helpers/validation.php';
require_once '../app/helpers/pagination.php';
require_once '../app/helpers/search.php';
require_once '../app/helpers/upload.php';
require_once '../app/helpers/quota.php';
require_once '../app/helpers/matching.php';

// 2.5 Services
require_once '../app/services/WorkloadService.php';
require_once '../app/services/ProgressService.php';
require_once '../app/services/LogService.php';

// 3. Middleware
require_once '../app/middleware/AuthMiddleware.php';
require_once '../app/middleware/RoleMiddleware.php';

// 4. Models
require_once '../app/models/User.php';
require_once '../app/models/Student.php';
require_once '../app/models/Lecturer.php';
require_once '../app/models/Semester.php';
require_once '../app/models/Topic.php';
require_once '../app/models/TopicRegistration.php';
require_once '../app/models/TopicAssignment.php';
require_once '../app/models/Milestone.php';
require_once '../app/models/Submission.php';
require_once '../app/models/Score.php';
require_once '../app/models/Notification.php';
require_once '../app/models/SystemLog.php';
require_once '../app/models/SupervisionAssignment.php';

// 5. Controllers
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/DashboardController.php';
require_once '../app/controllers/UserController.php';
require_once '../app/controllers/StudentController.php';
require_once '../app/controllers/LecturerController.php';
require_once '../app/controllers/SemesterController.php';
require_once '../app/controllers/TopicManagementController.php';
require_once '../app/controllers/TopicController.php';
require_once '../app/controllers/RegistrationController.php';
require_once '../app/controllers/AssignmentController.php';
require_once '../app/controllers/MilestoneController.php';
require_once '../app/controllers/SupervisionController.php';
require_once '../app/controllers/SubmissionController.php';
require_once '../app/controllers/ScoreController.php';
require_once '../app/controllers/NotificationController.php';
require_once '../app/controllers/LogController.php';
require_once '../app/controllers/ApiTopicController.php';

$page = $_GET['page'] ?? 'login';

switch ($page) {

    /*
    |--------------------------------------------------------------------------
    | AUTH
    |--------------------------------------------------------------------------
    */
    case 'login':
        (new AuthController())->login();
        break;

    case 'register':
        (new AuthController())->register();
        break;

    case 'logout':
        (new AuthController())->logout();
        break;

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    case 'dashboard':
        (new DashboardController())->index();
        break;

    /*
    |--------------------------------------------------------------------------
    | USERS
    |--------------------------------------------------------------------------
    */
    case 'users':
        (new UserController())->index();
        break;

    case 'user-create':
        (new UserController())->create();
        break;

    case 'user-store':
        (new UserController())->store();
        break;

    case 'user-show':
        (new UserController())->show();
        break;

    case 'user-edit':
        (new UserController())->edit();
        break;

    case 'user-update':
        (new UserController())->update();
        break;

    case 'user-delete':
        (new UserController())->delete();
        break;

    case 'user-lock':
        (new UserController())->lock();
        break;

    case 'user-unlock':
        (new UserController())->unlock();
        break;

    /*
    |--------------------------------------------------------------------------
    | STUDENTS
    |--------------------------------------------------------------------------
    */
    case 'students':
        (new StudentController())->index();
        break;

    case 'student-create':
        (new StudentController())->create();
        break;

    case 'student-store':
        (new StudentController())->store();
        break;

    case 'student-show':
        (new StudentController())->show();
        break;

    case 'student-edit':
        (new StudentController())->edit();
        break;

    case 'student-update':
        (new StudentController())->update();
        break;

    case 'student-delete':
        (new StudentController())->delete();
        break;

    /*
    |--------------------------------------------------------------------------
    | LECTURERS
    |--------------------------------------------------------------------------
    */
    case 'lecturers':
        (new LecturerController())->index();
        break;

    case 'lecturer-create':
        (new LecturerController())->create();
        break;

    case 'lecturer-store':
        (new LecturerController())->store();
        break;

    case 'lecturer-show':
        (new LecturerController())->show();
        break;

    case 'lecturer-edit':
        (new LecturerController())->edit();
        break;

    case 'lecturer-update':
        (new LecturerController())->update();
        break;

    case 'lecturer-delete':
        (new LecturerController())->delete();
        break;

    /*
    |--------------------------------------------------------------------------
    | SEMESTERS
    |--------------------------------------------------------------------------
    */
    case 'semesters':
        (new SemesterController())->index();
        break;

    case 'semester-create':
        (new SemesterController())->create();
        break;

    case 'semester-store':
        (new SemesterController())->store();
        break;

    case 'semester-edit':
        (new SemesterController())->edit();
        break;

    case 'semester-update':
        (new SemesterController())->update();
        break;

    case 'semester-delete':
        (new SemesterController())->delete();
        break;

    /*
    |--------------------------------------------------------------------------
    | TOPIC MANAGEMENT (Topics + Registrations gộp 1 trang)
    |--------------------------------------------------------------------------
    */
    case 'topic-management':
        (new TopicManagementController())->index();
        break;

    /*
    |--------------------------------------------------------------------------
    | TOPICS
    |--------------------------------------------------------------------------
    */


    case 'topic-create':
        (new TopicController())->create();
        break;

    case 'topic-store':
        (new TopicController())->store();
        break;

    case 'topic-edit':
        (new TopicController())->edit();
        break;

    case 'topic-update':
        (new TopicController())->update();
        break;

    case 'topic-delete':
        (new TopicController())->delete();
        break;

    case 'topic-status':
        (new TopicController())->updateStatus();
        break;

    /*
    |--------------------------------------------------------------------------
    | REGISTRATIONS
    |--------------------------------------------------------------------------
    */


    case 'registration-create':
        (new RegistrationController())->create();
        break;

    case 'registration-store':
        (new RegistrationController())->store();
        break;

    case 'registration-edit':
        (new RegistrationController())->edit();
        break;

    case 'registration-update':
        (new RegistrationController())->update();
        break;

    case 'registration-delete':
        (new RegistrationController())->delete();
        break;

    case 'registration-register':
        (new RegistrationController())->register();
        break;

    case 'registration-status':
        (new RegistrationController())->updateStatus();
        break;

    /*
    |--------------------------------------------------------------------------
    | SUPERVISIONS
    |--------------------------------------------------------------------------
    */
    case 'supervisions':
        (new SupervisionController())->index();
        break;

    case 'supervision-create':
        (new SupervisionController())->create();
        break;

    case 'supervision-store':
        (new SupervisionController())->store();
        break;

    case 'supervision-delete':
        (new SupervisionController())->delete();
        break;

    /*
    |--------------------------------------------------------------------------
    | ASSIGNMENTS
    |--------------------------------------------------------------------------
    */
    case 'assignments':
        (new AssignmentController())->index();
        break;

    case 'assignment-create':
        (new AssignmentController())->create();
        break;

    case 'assignment-store':
        (new AssignmentController())->store();
        break;

    case 'assignment-edit':
        (new AssignmentController())->edit();
        break;

    case 'assignment-update':
        (new AssignmentController())->update();
        break;

    case 'assignment-delete':
        (new AssignmentController())->delete();
        break;

    /*
    |--------------------------------------------------------------------------
    | MILESTONES
    |--------------------------------------------------------------------------
    */
    case 'milestones':
        (new MilestoneController())->index();
        break;

    case 'milestone-create':
        (new MilestoneController())->create();
        break;

    case 'milestone-store':
        (new MilestoneController())->store();
        break;

    case 'milestone-edit':
        (new MilestoneController())->edit();
        break;

    case 'milestone-update':
        (new MilestoneController())->update();
        break;

    case 'milestone-delete':
        (new MilestoneController())->delete();
        break;

    /*
    |--------------------------------------------------------------------------
    | SUBMISSIONS
    |--------------------------------------------------------------------------
    */
    case 'submissions':
        (new SubmissionController())->index();
        break;

    case 'submission-create':
        (new SubmissionController())->create();
        break;

    case 'submission-store':
        (new SubmissionController())->store();
        break;

    case 'submission-show':
        (new SubmissionController())->show();
        break;

    case 'submission-status':
        (new SubmissionController())->updateStatus();
        break;

    case 'submission-delete':
        (new SubmissionController())->delete();
        break;

    /*
    |--------------------------------------------------------------------------
    | SCORES
    |--------------------------------------------------------------------------
    */
    case 'scores':
        (new ScoreController())->index();
        break;

    case 'score-create':
        (new ScoreController())->create();
        break;

    case 'score-store':
        (new ScoreController())->store();
        break;

    case 'score-edit':
        (new ScoreController())->edit();
        break;

    case 'score-update':
        (new ScoreController())->update();
        break;

    case 'score-delete':
        (new ScoreController())->delete();
        break;

    /*
    |--------------------------------------------------------------------------
    | NOTIFICATIONS
    |--------------------------------------------------------------------------
    */
    case 'notifications':
        (new NotificationController())->index();
        break;

    case 'notification-read':
        (new NotificationController())->read();
        break;

    case 'notification-read-all':
        (new NotificationController())->readAll();
        break;

    case 'notification-delete':
        (new NotificationController())->delete();
        break;

    /*
    |--------------------------------------------------------------------------
    | LOGS
    |--------------------------------------------------------------------------
    */
    case 'logs':
        (new LogController())->index();
        break;

    /*
    |--------------------------------------------------------------------------
    | API
    |--------------------------------------------------------------------------
    */
    case 'api-topics':
        (new ApiTopicController())->index();
        break;

    case 'api-topic-store':
        (new ApiTopicController())->store();
        break;

    case 'api-topic-delete':
        (new ApiTopicController())->delete();
        break;

    /*
    |--------------------------------------------------------------------------
    | DEFAULT
    |--------------------------------------------------------------------------
    */
    default:
        abort(404);
        break;
}
