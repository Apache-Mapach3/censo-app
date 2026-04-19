<?php

$host     = getenv('DB_HOST') ?: $_SERVER['DB_HOST'] ?: 'localhost';
$port     = getenv('DB_PORT') ?: $_SERVER['DB_PORT'] ?: '3306';
$dbname   = getenv('DB_NAME') ?: $_SERVER['DB_NAME'] ?: 'censo_app';
$username = getenv('DB_USER') ?: $_SERVER['DB_USER'] ?: 'root';
$password = getenv('DB_PASS') ?: $_SERVER['DB_PASS'] ?: '';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    
    $pdo = new PDO($dsn, $username, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {

    die("Error de conexión a la base de datos: " . $e->getMessage());
}