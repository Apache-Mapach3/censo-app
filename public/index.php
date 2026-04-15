<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

// --- 1. IMPORTACIONES (Namespaces) ---
use App\Usuario\Infrastructure\Persistence\MySQLUsuarioRepository;
use App\Usuario\Application\UseCase\CrearUsuarioUseCase;
use App\Usuario\Application\UseCase\AutenticarUsuarioUseCase;
use App\Usuario\Infrastructure\Controllers\UsuarioController;

use App\Censo\Infrastructure\Persistence\MySQLCensoRepository;
use App\Censo\Application\UseCase\RegistrarCensoUseCase;
use App\Censo\Application\UseCase\ListarCensosUseCase;
use App\Censo\Application\UseCase\ObtenerCensoPorIdUseCase;
use App\Censo\Application\UseCase\ActualizarCensoUseCase;
use App\Censo\Application\UseCase\EliminarCensoUseCase;
use App\Censo\Infrastructure\Controllers\CensoController;
use App\Censo\Infrastructure\Controllers\ListarCensosController;
use App\Censo\Infrastructure\Controllers\GestionarCensoController;

// --- 2. INICIALIZACIÓN DE COMPONENTES ---

// Módulo Usuario
$usuarioRepo       = new MySQLUsuarioRepository($pdo);
$usuarioController = new UsuarioController(
    new CrearUsuarioUseCase($usuarioRepo),
    new AutenticarUsuarioUseCase($usuarioRepo)
);

// Módulo Censo
$censoRepo         = new MySQLCensoRepository($pdo);
$censoController   = new CensoController(new RegistrarCensoUseCase($censoRepo));
$listarController  = new ListarCensosController(new ListarCensosUseCase($censoRepo));
$gestionController = new GestionarCensoController(
    new ObtenerCensoPorIdUseCase($censoRepo),
    new ActualizarCensoUseCase($censoRepo),
    new EliminarCensoUseCase($censoRepo)
);

// --- 3. ENRUTADOR (Lógica de navegación) ---

// Obtenemos la acción de POST o GET
$action = $_POST['action'] ?? $_GET['action'] ?? null;

switch ($action) {
    // --- ACCIONES DE USUARIO ---
    case 'register_user':
        $usuarioController->registrar($_POST);
        break;

    case 'login_user':
        $usuarioController->login($_POST);
        break;

    // --- ACCIONES DE CENSO ---
    case 'register_censo':
        $censoController->registrar($_POST);
        break;

    case 'listar_censos':
        $listarController->listar();
        break;

    case 'editar_censo':
        $gestionController->cargarFormularioEdicion((int)$_GET['id']);
        break;

    case 'actualizar_censo':
        $gestionController->actualizar($_POST);
        break;

    case 'eliminar_censo':
        $gestionController->eliminar((int)$_GET['id']);
        break;

    // --- PÁGINA POR DEFECTO ---
    default:
        // Si no hay acción, mandamos al login o a una página de bienvenida
        header("Location: login.html");
        exit();
}