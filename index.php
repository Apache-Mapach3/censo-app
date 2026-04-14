<?php

// carga el Autoload de Composer para manejar las dependencias y el autoloading de clases
require_once __DIR__ . '/vendor/autoload.php';

use App\Usuario\Infrastructure\Persistence\MySQLUsuarioRepository;
use App\Usuario\Application\UseCase\CrearUsuarioUseCase;
use App\Usuario\Application\UseCase\AutenticarUsuarioUseCase;
use App\Usuario\Infrastructure\Controllers\UsuarioController;

use App\Censo\Infrastructure\Persistence\MySQLCensoRepository;
use App\Censo\Application\UseCase\RegistrarCensoUseCase;
use App\Censo\Infrastructure\Controllers\CensoController;

// Conexión a la Base de Datos (PDO)
$host = 'localhost';
$db   = 'censo_app'; // Asegúrate de crear esta base de datos en MySQL/Laragon
$user = 'root';      // Usuario por defecto de Laragon
$pass = '';          // Clave por defecto de Laragon (suele estar vacía)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\PDOException $e) {
    die("Error de conexion a la base de datos: " . $e->getMessage());
}

// Inyección de Dependencias (El "cableado" de la Arquitectura Hexagonal)

$usuarioRepo = new MySQLUsuarioRepository($pdo);
$crearUsuarioUC = new CrearUsuarioUseCase($usuarioRepo);
$autenticarUsuarioUC = new AutenticarUsuarioUseCase($usuarioRepo);
$usuarioController = new UsuarioController($crearUsuarioUC, $autenticarUsuarioUC);

// Censos
$censoRepo = new MySQLCensoRepository($pdo);
$registrarCensoUC = new RegistrarCensoUseCase($censoRepo);
$censoController = new CensoController($registrarCensoUC);

// Mini Enrutador (Para recibir peticiones de los formularios)
$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'registrar_usuario':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioController->registrar($_POST);
        }
        break;
        
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioController->login($_POST);
        }
        break;

    case 'registrar_censo':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $censoController->registrar($_POST);
        }
        break;

    default:
        echo "<h1>Sistema de Censo Activo</h1>";
        echo "<p>Arquitectura Hexagonal conectada y esperando peticiones.</p>";
        break;
}