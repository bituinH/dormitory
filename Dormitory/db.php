<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'dormitory_management';
$port = 3307;

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $columnCheck = $pdo->prepare("
        SELECT COUNT(*)
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = ?
            AND TABLE_NAME = 'users'
            AND COLUMN_NAME = 'email'
    ");
    $columnCheck->execute([$dbname]);

    if ((int) $columnCheck->fetchColumn() === 0) {
        $pdo->exec("ALTER TABLE users ADD email VARCHAR(190) NULL UNIQUE AFTER username");
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}                                                                   
