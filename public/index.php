<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

require_once __DIR__ . '/../config/database.php';

$orgId = (int)($_SESSION['organizacion_id'] ?? 0);

// IMPORTACIÓN DE CLASES (USUARIO)
use App\Usuario\Infrastructure\Persistence\MySQLUsuarioRepository;
use App\Usuario\Infrastructure\Persistence\MySQLTokenRecuperacionRepository;
use App\Usuario\Application\UseCase\CrearUsuarioUseCase;
use App\Usuario\Application\UseCase\AutenticarUsuarioUseCase;
use App\Usuario\Application\UseCase\ListarUsuariosUseCase;
use App\Usuario\Application\UseCase\ActualizarUsuarioUseCase;
use App\Usuario\Application\UseCase\EliminarUsuarioUseCase;
use App\Usuario\Application\UseCase\SolicitarRecuperacionUseCase;
use App\Usuario\Application\UseCase\RestablecerClaveUseCase;
use App\Usuario\Infrastructure\Controllers\UsuarioController;
use App\Usuario\Infrastructure\Mail\PHPMailerEmailSender;

// IMPORTACIÓN DE CLASES (ORGANIZACIÓN)
use App\Organizacion\Infrastructure\Persistence\MySQLOrganizacionRepository;
use App\Organizacion\Application\UseCase\CrearOrganizacionConAdminUseCase;

// IMPORTACIÓN DE CLASES (CENSO)
use App\Censo\Infrastructure\Persistence\MySQLCensoRepository;
use App\Censo\Application\UseCase\RegistrarCensoUseCase;
use App\Censo\Application\UseCase\ListarCensosUseCase;
use App\Censo\Application\UseCase\ObtenerCensoPorIdUseCase;
use App\Censo\Application\UseCase\ActualizarCensoUseCase;
use App\Censo\Application\UseCase\EliminarCensoUseCase;
use App\Censo\Infrastructure\Controllers\CensoController;
use App\Censo\Infrastructure\Controllers\ListarCensosController;
use App\Censo\Infrastructure\Controllers\GestionarCensoController;

// REPOSITORIOS
$usuarioRepo = new MySQLUsuarioRepository($pdo);
$tokenRepo   = new MySQLTokenRecuperacionRepository($pdo);
$orgRepo     = new MySQLOrganizacionRepository($pdo);
$emailSender = new PHPMailerEmailSender();

// CONTROLADOR DE USUARIO — ahora recibe 9 parámetros (+ $orgRepo)
$usuarioController = new UsuarioController(
    new CrearUsuarioUseCase($usuarioRepo, $orgRepo),
    new AutenticarUsuarioUseCase($usuarioRepo),
    new ListarUsuariosUseCase($usuarioRepo),
    new ActualizarUsuarioUseCase($usuarioRepo),
    new EliminarUsuarioUseCase($usuarioRepo),
    new SolicitarRecuperacionUseCase($usuarioRepo, $tokenRepo, $emailSender),
    new RestablecerClaveUseCase($usuarioRepo, $tokenRepo),
    new CrearOrganizacionConAdminUseCase($orgRepo, $usuarioRepo),
    $orgRepo 
);
// CONTROLADORES DE CENSO
$censoRepo         = new MySQLCensoRepository($pdo);
$actualizarUseCase = new ActualizarCensoUseCase($censoRepo);

$censoController   = new CensoController(
    new RegistrarCensoUseCase($censoRepo),
    $actualizarUseCase
);
$listarController  = new ListarCensosController(new ListarCensosUseCase($censoRepo));
$gestionController = new GestionarCensoController(
    new ObtenerCensoPorIdUseCase($censoRepo),
    $actualizarUseCase,
    new EliminarCensoUseCase($censoRepo)
);

// ENRUTADOR
$action = $_POST['action'] ?? $_GET['action'] ?? null;

switch ($action) {

    case 'register':
    case 'register_user':
        $usuarioController->registrar($_POST);
        break;

    case 'login':
    case 'login_user':
        $usuarioController->login($_POST);
        break;

    case 'listar_usuarios':
        $usuarioController->listar($orgId);
        break;

    case 'editar_usuario':
        $usuarioController->cargarFormularioEdicion((int)($_GET['id'] ?? 0));
        break;

    case 'actualizar_usuario':
        $usuarioController->actualizar($_POST);
        break;

    case 'eliminar_usuario':
        $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
        $usuarioController->eliminar($id);
        break;

    case 'recuperar_clave':
        $usuarioController->mostrarFormularioRecuperacion();
        break;

    case 'solicitar_recuperacion':
        $usuarioController->solicitarRecuperacion($_POST);
        break;

    case 'restablecer_clave':
        $token = $_GET['token'] ?? $_POST['token'] ?? '';
        $usuarioController->mostrarFormularioRestablecer($token);
        break;

    case 'confirmar_restablecer':
        $usuarioController->restablecerClave($_POST);
        break;

    case 'register_censo':
        $censoController->registrar($_POST, $orgId);
        break;

    case 'mostrar_registro':
        require_once __DIR__ . '/views/registro_censo.php';
        break;

    case 'listar_censos':
        $listarController->listar($orgId);
        break;

    case 'editar_censo':
        $gestionController->cargarFormularioEdicion((int)($_GET['id'] ?? 0));
        break;

    case 'actualizar_censo':
        $gestionController->actualizar($_POST);
        break;

    case 'eliminar_censo':
        $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);
        $gestionController->eliminar($id);
        break;

    default:
        header("Location: login.html");
        exit();
}