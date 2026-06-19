<?php
require 'config/config.php';
require 'core/Database.php';

$db = Database::getInstance()->getConnection();
$db->exec("SET NAMES 'utf8mb4'");

// Update students
$db->exec("UPDATE students SET faculty = 'Khoa Ngôn ngữ' WHERE id IN (1, 7, 8)");
$db->exec("UPDATE students SET faculty = 'Khoa CNTT' WHERE id IN (2, 4)");
$db->exec("UPDATE students SET faculty = 'Khoa Kinh tế' WHERE id IN (5)");
$db->exec("UPDATE students SET faculty = 'Khoa CNTT' WHERE id IN (6)"); // Change IT to Khoa CNTT

// Update lecturers
$db->exec("UPDATE lecturers SET faculty = 'Khoa Kinh tế' WHERE id IN (1, 3, 7)");
$db->exec("UPDATE lecturers SET faculty = 'Khoa CNTT' WHERE id IN (2, 4, 8)");
$db->exec("UPDATE lecturers SET faculty = 'Khoa Ngôn ngữ' WHERE id IN (6)");

echo "Done fixing DB encodings.\n";
