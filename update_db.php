<?php
require 'config/config.php';
require 'core/Database.php';
$db = Database::getInstance()->getConnection();
$db->exec("UPDATE topic_registrations SET status='registered' WHERE status='pending'");
echo 'Updated database!';
