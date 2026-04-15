<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

use App\Usuario\Infrastructure\Persistence\MySQLUsuarioRepository;
use App\Usuario\Application\UseCase\CrearUsuarioUseCase;
use App\Usuario\Application\UseCase\AutenticarUsuarioUseCase;
use App\Usuario\Infrastructure\Controllers\UsuarioController;


use App\Censo\Infrastructure\Persistence\MySQLCensoRepository;
use App\Censo\Application\UseCase\RegistrarCensoUseCase;
use App\Censo\Infrastructure\Controllers\CensoController;

// Inyección de dependencias — Usuario
$repo   = new MySQLUsuarioRepository($pdo);
$crear  = new CrearUsuarioUseCase($repo);
$login  = new AutenticarUsuarioUseCase($repo);
$usuarioController = new UsuarioController($crear, $login);


$censoRepo       = new MySQLCensoRepository($pdo);
$registrarCenso  = new RegistrarCensoUseCase($censoRepo);
$censoController = new CensoController($registrarCenso);

// Router
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    switch ($_POST['action']) {
        // El usuario quiere CREAR una cuenta nueva
        case 'register_user':
            // CAMBIO: Antes decía $userController, ahora $usuarioController
            $usuarioController->registrar($_POST); 
            break;
            
        // El usuario ya existe y quiere ENTRAR
        case 'login_user':
            // CAMBIO: Antes decía $userController, ahora $usuarioController
            $usuarioController->login($_POST);
            break;

        // Registrar datos del censo
        case 'register_censo':
            $censoController->registrar($_POST);
            break;

        default:
            echo "Acción no reconocida.";
            break;
    }
}