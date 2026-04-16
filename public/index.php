<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

session_start();

// IMPORTACIONES
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

// INICIALIZACIÓN

$usuarioRepo       = new MySQLUsuarioRepository($pdo);
$usuarioController = new UsuarioController(
    new CrearUsuarioUseCase($usuarioRepo),
    new AutenticarUsuarioUseCase($usuarioRepo)
);

$censoRepo         = new MySQLCensoRepository($pdo);
$censoController   = new CensoController(new RegistrarCensoUseCase($censoRepo));
$listarController  = new ListarCensosController(new ListarCensosUseCase($censoRepo));
$gestionController = new GestionarCensoController(
    new ObtenerCensoPorIdUseCase($censoRepo),
    new ActualizarCensoUseCase($censoRepo),
    new EliminarCensoUseCase($censoRepo)
);

// ENRUTADOR

// BUG CORREGIDO: los formularios HTML envían 'register' y 'login',
// no 'register_user' ni 'login_user'. Se unifican aquí.
$action = $_POST['action'] ?? $_GET['action'] ?? null;

switch ($action) {

    // USUARIO 
    case 'register':         // viene de registro.html
    case 'register_user':
        $usuarioController->registrar($_POST);
        break;

    case 'login':            // viene de login.html
    case 'login_user':
        $usuarioController->login($_POST);
        break;

    // CENSO
    case 'register_censo':
        $censoController->registrar($_POST);
        break;

    case 'mostrar_registro': // botón "+ Nuevo Censo"
        require_once __DIR__ . '/views/registro_censo.php';
        break;

    case 'listar_censos':
        $listarController->listar();
        break;

    case 'editar_censo':
        $gestionController->cargarFormularioEdicion((int)($_GET['id'] ?? 0));
        break;

    case 'actualizar_censo':
        $gestionController->actualizar($_POST);
        break;

    // BUG CORREGIDO: el formulario de eliminar envía el id por POST,
    // así que se lee de $_POST, no de $_GET.
    case 'eliminar_censo':
        $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
        $gestionController->eliminar($id);
        break;

    // DEFAULT
    default:
        header("Location: login.html");
        exit();
}