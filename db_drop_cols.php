<?php
require 'config/config.php';
require 'core/Database.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Students table
    $db->exec("ALTER TABLE students DROP COLUMN IF EXISTS student_code;");
    $db->exec("ALTER TABLE students DROP COLUMN IF EXISTS phone;");
    
    // Lecturers table
    $db->exec("ALTER TABLE lecturers DROP COLUMN IF EXISTS lecturer_code;");
    $db->exec("ALTER TABLE lecturers DROP COLUMN IF EXISTS phone;");

    echo "Columns dropped successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
