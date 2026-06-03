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

        $status = $_SESSION['user']['status'] ?? 'active';

        if($status == 'locked'){

            session_destroy();

            die("Your account has been locked.");
        }
    }
}