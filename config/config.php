<?php

session_start();

date_default_timezone_set('Asia/Ho_Chi_Minh');

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('BASE_URL', $protocol . $host . '/Group7_CapstoneManager/public/');

define('APP_ROOT', dirname(dirname(__FILE__)));

define('UPLOAD_PATH', APP_ROOT . '/../public/assets/uploads/');

define('MAX_FILE_SIZE', 10 * 1024 * 1024);

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'capstone_manager');