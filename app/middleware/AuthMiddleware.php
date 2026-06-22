<?php

class AuthMiddleware {

    public static function check(){
        /*
        |--------------------------------------------------------------------------
        | CHECK LOGIN
        |--------------------------------------------------------------------------
        */
        if(!isset($_SESSION['user'])){

            header("Location: index.php?page=login");
            exit;
        }
        /*
        |--------------------------------------------------------------------------
        | SAFE STATUS CHECK
        |--------------------------------------------------------------------------
        */
        $userModel = new User();
        $user = $userModel->findByEmail($_SESSION['user']['email']);

        if(!$user || $user['status'] == 'locked'){
            session_destroy();
            abort(403, "Your account has been locked.");
        }
    }
}