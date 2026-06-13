<?php

/**
 * Redirect to a page within the app.
 *
 * Usage:
 *   redirect('dashboard')          → BASE_URL . index.php?page=dashboard
 *   redirect('users')              → BASE_URL . index.php?page=users
 */
function redirect($page) {
    header("Location: " . BASE_URL . "index.php?page=" . $page);
    exit;
}
