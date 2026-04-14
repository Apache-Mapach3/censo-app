<?php
// Archivo: public/index.php

// Requerir dependencias con rutas relativas correctas
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

use App\Usuario\Infrastructure\Persistence\MySQLUsuarioRepository;
use App\Usuario\Application\UseCase\CrearUsuarioUseCase;
use App\Usuario\Application\UseCase\AutenticarUsuarioUseCase;
use App\Usuario\Infrastructure\Controllers\UsuarioController;

// Instancias (Inyección de dependencias)
$repo = new MySQLUsuarioRepository($pdo);
$crear = new CrearUsuarioUseCase($repo);
$login = new AutenticarUsuarioUseCase($repo);

// Instanciamos el controlador con ambos casos de uso
$controller = new UsuarioController($crear, $login);

// Simulación de request (Enrutador básico)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    if ($_POST['action'] === 'register') {
        

        $controller->registrar($_POST);

    } elseif ($_POST['action'] === 'login') {
        
        $controller->login($_POST);
        
    }
    
} else {
    echo "<h1>Sistema de Censo (Estructura Profesional)</h1>";
}