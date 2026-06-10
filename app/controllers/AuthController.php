<?php

class AuthController {

    private $userModel;

    public function __construct(){

        $this->userModel = new User();
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(){

        if(isset($_SESSION['user'])){

            header("Location: index.php?page=dashboard");
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            /*
            |--------------------------------------------------------------------------
            | VALIDATION
            |--------------------------------------------------------------------------
            */

            if(empty($email) || empty($password)){

                $error = "Please fill all fields";

                require '../app/views/auth/login.php';
                return;
            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){

                $error = "Invalid email format";

                require '../app/views/auth/login.php';
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | FIND USER
            |--------------------------------------------------------------------------
            */

            $user = $this->userModel->findByEmail($email);

            if(!$user){

                $error = "Account not found";

                require '../app/views/auth/login.php';
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | CHECK STATUS
            |--------------------------------------------------------------------------
            */

            if($user['status'] == 'locked'){

                $error = "Your account has been locked";

                require '../app/views/auth/login.php';
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | VERIFY PASSWORD
            |--------------------------------------------------------------------------
            */

            if(!password_verify($password, $user['password'])){

                $error = "Invalid password";

                require '../app/views/auth/login.php';
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | LOGIN SUCCESS
            |--------------------------------------------------------------------------
            */

            // Build base session
            $_SESSION['user'] = [
                'id'    => $user['id'],
                'email' => $user['email'],
                'role'  => $user['role']
            ];

            // Attach student_id / lecturer_id so controllers can use them directly
            if ($user['role'] === 'student') {
                $studentModel = new Student();
                $student = $studentModel->findByUserId($user['id']);
                if ($student) {
                    $_SESSION['user']['student_id'] = $student['id'];
                }
            }
            if ($user['role'] === 'lecturer') {
                $lecturerModel = new Lecturer();
                $lecturer = $lecturerModel->findByUserId($user['id']);
                if ($lecturer) {
                    $_SESSION['user']['lecturer_id'] = $lecturer['id'];
                }
            }

            /*
            |--------------------------------------------------------------------------
            | SYSTEM LOG
            |--------------------------------------------------------------------------
            */

            if(class_exists('SystemLog')){

                $logModel = new SystemLog();

                $logModel->create([

                    'user_id' => $user['id'],
                    'action' => 'User logged in'

                ]);
            }

            header("Location: index.php?page=dashboard");
            exit;
        }

        require '../app/views/auth/login.php';
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

    public function register(){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            /*
            |--------------------------------------------------------------------------
            | GET DATA
            |--------------------------------------------------------------------------
            */

            $full_name = trim($_POST['full_name']);
            $student_code = trim($_POST['student_code']);
            $phone = trim($_POST['phone']);

            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            $role = $_POST['role'] ?? '';

            /*
            |--------------------------------------------------------------------------
            | VALIDATION
            |--------------------------------------------------------------------------
            */

            if(
                empty($full_name) ||
                empty($email) ||
                empty($password)
            ){
                $error = "Vui lòng điền đầy đủ thông tin bắt buộc.";
                require '../app/views/auth/register.php';
                return;
            }

            // Bắt buộc chọn role hợp lệ
            if(!in_array($role, ['student', 'lecturer'])){
                $error = "Vui lòng chọn vai trò: Sinh viên hoặc Giảng viên.";
                require '../app/views/auth/register.php';
                return;
            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $error = "Email không hợp lệ.";
                require '../app/views/auth/register.php';
                return;
            }

            if(strlen($password) < 6){
                $error = "Mật khẩu phải có ít nhất 6 ký tự.";
                require '../app/views/auth/register.php';
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | CHECK EMAIL EXIST
            |--------------------------------------------------------------------------
            */

            $exist = $this->userModel->findByEmail($email);

            if($exist){

                $error = "Email already exists";

                require '../app/views/auth/register.php';
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE USER
            |--------------------------------------------------------------------------
            */

            $userId = $this->userModel->create([

                'email' => $email,
                'password' => $password,
                'role' => $role
            ]);

            /*
            |--------------------------------------------------------------------------
            | CREATE STUDENT
            |--------------------------------------------------------------------------
            */

            if($role == 'student'){

                $studentModel = new Student();

                $studentModel->create([

                    'user_id' => $userId,
                    'student_code' => $student_code,
                    'full_name' => $full_name,
                    'phone' => $phone
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE LECTURER
            |--------------------------------------------------------------------------
            */

            if($role == 'lecturer'){

                $lecturerModel = new Lecturer();

                $lecturerModel->create([
                    'user_id'   => $userId,
                    'full_name' => $full_name,
                    'expertise' => ''
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | SUCCESS
            |--------------------------------------------------------------------------
            */

            $_SESSION['success'] = "Register successful";

            header("Location: index.php?page=login");
            exit;
        }

        require '../app/views/auth/register.php';
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(){

        if(isset($_SESSION['user'])){

            if(class_exists('SystemLog')){

                $logModel = new SystemLog();

                $logModel->create([

                    'user_id' => $_SESSION['user']['id'],
                    'action' => 'User logged out'

                ]);
            }
        }

        session_destroy();

        header("Location: index.php?page=login");
        exit;
    }
}