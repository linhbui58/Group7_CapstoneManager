<?php

function auth(){

    return $_SESSION['user'] ?? null;
}

function userId(){

    return $_SESSION['user']['id'] ?? null;
}

function userRole(){

    return $_SESSION['user']['role'] ?? null;
}

function isAdmin(){

    return userRole() == 'admin';
}

function isStudent(){

    return userRole() == 'student';
}

function isLecturer(){

    return userRole() == 'lecturer';
}