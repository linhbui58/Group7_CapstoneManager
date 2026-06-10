<?php

session_start();

date_default_timezone_set('Asia/Ho_Chi_Minh');

define('BASE_URL', 'http://localhost/Group7_CapstoneManager-main/public/');

define('APP_ROOT', dirname(dirname(__FILE__)));

define('UPLOAD_PATH', APP_ROOT . '/../public/assets/uploads/');

define('MAX_FILE_SIZE', 10 * 1024 * 1024);