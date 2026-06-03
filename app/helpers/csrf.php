<?php

function generateCSRF(){

    if(empty($_SESSION['csrf_token'])){

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrfField(){

    return '<input type="hidden" name="csrf_token" value="' 
            . generateCSRF() . '">';
}

function verifyCSRF(){

    if(
        !isset($_POST['csrf_token']) ||
        $_POST['csrf_token'] !== $_SESSION['csrf_token']
    ){

        die("CSRF Token Invalid");
    }
}