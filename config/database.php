<?php


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
}