<?php

function jsonResponse($status, $message, $data = []){

    header('Content-Type: application/json');

    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);

    exit;
}

function abort($code = 500, $errorMessage = null) {
    http_response_code($code);
    $view = "../app/views/errors/{$code}.php";
    if (file_exists($view)) {
        require_once $view;
    } else {
        echo "<h1>{$code} Error</h1>";
        if ($errorMessage) {
            echo "<p>" . htmlspecialchars($errorMessage) . "</p>";
        }
    }
    exit;
}