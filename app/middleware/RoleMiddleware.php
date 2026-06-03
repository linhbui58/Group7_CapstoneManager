<?php

class RoleMiddleware {

    public static function check($roles = []){

        if(!isset($_SESSION['user'])){

            header("Location: index.php?page=login");
            exit;
        }

        $userRole = $_SESSION['user']['role'];

        if(!in_array($userRole, $roles)){

            http_response_code(403);

            die("403 Forbidden - Access Denied");
        }
    }
}