<?php
<<<<<<< HEAD


$host = 'localhost';
$dbname = 'censo_app'; 
$username = 'root';    
$password = '';        

try {
    // Intentamos conectar a la base de datos usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Configuramos PDO para que nos lance excepciones si hay errores de SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // Si algo falla, detenemos la ejecución y mostramos el error
    die("Error crítico - No se pudo conectar a la base de datos: " . $e->getMessage());
=======
// Archivo: config/database.php

$host = 'localhost';
$db = 'censo_app';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\PDOException $e) {
    die("Error de conexion: " . $e->getMessage());
>>>>>>> dd5764e2238eda856dc2f517e6feeab9ff8c294b
}