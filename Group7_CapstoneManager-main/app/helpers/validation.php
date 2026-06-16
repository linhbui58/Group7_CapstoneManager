<?php

function validateRequired($fields = []){

    foreach($fields as $field){

        if(empty(trim($field))){

            return false;
        }
    }

    return true;
}

function validateEmail($email){

    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePassword($password){

    return strlen($password) >= 6;
}