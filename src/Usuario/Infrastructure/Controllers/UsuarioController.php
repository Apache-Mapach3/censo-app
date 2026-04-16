<?php

namespace App\Usuario\Infrastructure\Controllers;

use App\Usuario\Application\UseCase\CrearUsuarioUseCase;
use App\Usuario\Application\UseCase\AutenticarUsuarioUseCase;
use App\Usuario\Application\UseCase\ListarUsuariosUseCase;
use App\Usuario\Application\UseCase\ActualizarUsuarioUseCase;
use App\Usuario\Application\UseCase\EliminarUsuarioUseCase;
use App\Usuario\Application\UseCase\SolicitarRecuperacionUseCase;
use App\Usuario\Application\UseCase\RestablecerClaveUseCase;

class UsuarioController {

    public function __construct(
        private CrearUsuarioUseCase          $crearUseCase,
        private AutenticarUsuarioUseCase     $autenticarUseCase,
        private ListarUsuariosUseCase        $listarUseCase,
        private ActualizarUsuarioUseCase     $actualizarUseCase,
        private EliminarUsuarioUseCase       $eliminarUseCase,
        private SolicitarRecuperacionUseCase $solicitarRecuperacionUseCase,
        private RestablecerClaveUseCase      $restablecerClaveUseCase
    ) {}

    public function registrar(array $request): void {
        try {
            $this->crearUseCase->execute(
                $request['nombre'] ?? '',
                $request['clave']  ?? '',
                $request['rol']    ?? '',
                $request['correo'] ?? ''
            );
            header("Location: login.html?registered=1");
            exit();
        } catch (\Exception $e) {
            echo "<p style='color:red;font-family:sans-serif;padding:20px'>Error: "
                . htmlspecialchars($e->getMessage())
                . " <a href='registro.html'>Volver</a></p>";
        }
    }

    public function login(array $request): void {
        $usuario = $this->autenticarUseCase->execute(
            $request['nombre'] ?? '',
            $request['clave']  ?? ''
        );

        if ($usuario) {
            $_SESSION['usuario_id']     = $usuario->getId();
            $_SESSION['usuario_nombre'] = $usuario->getNombre();
            $_SESSION['usuario_rol']    = $usuario->getRol();
            header("Location: index.php?action=listar_censos");
            exit();
        } else {
            echo "<p style='color:red;font-family:sans-serif;padding:20px'>Credenciales incorrectas. "
                . "<a href='login.html'>Volver</a></p>";
        }
    }

    public function listar(): void {
        $usuarios = $this->listarUseCase->execute();
        require_once __DIR__ . '/../../../../public/views/listar_usuarios.php';
    }

    public function cargarFormularioEdicion(int $id): void {
        try {
            // Reutilizamos el repositorio a través del use case de actualizar
            // pero necesitamos obtener el usuario primero, así que lo buscamos vía listar
            $todos   = $this->listarUseCase->execute();
            $usuario = null;
            foreach ($todos as $u) {
                if ($u->getId() === $id) { $usuario = $u; break; }
            }
            if (!$usuario) throw new \InvalidArgumentException("Usuario no encontrado");
            require_once __DIR__ . '/../../../../public/views/editar_usuario.php';
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    public function actualizar(array $request): void {
        try {
            $this->actualizarUseCase->execute(
                (int)($request['id']         ?? 0),
                $request['nombre']           ?? '',
                $request['correo']           ?? '',
                $request['rol']              ?? '',
                $request['nueva_clave']      ?? ''
            );
            header('Location: index.php?action=listar_usuarios');
            exit();
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    public function eliminar(int $id): void {
        try {
            $this->eliminarUseCase->execute($id);
            header('Location: index.php?action=listar_usuarios');
            exit();
        } catch (\Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    public function solicitarRecuperacion(array $request): void {
        $correo  = $request['correo'] ?? '';
        $baseUrl = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . \dirname($_SERVER['SCRIPT_NAME']);

        try {
            $this->solicitarRecuperacionUseCase->execute($correo, rtrim($baseUrl, '/'));
            $mensaje = "Si ese correo está registrado, recibirás un enlace en breve.";
        } catch (\Exception $e) {
            $mensaje = "Error: " . $e->getMessage();
        }

        require_once __DIR__ . '/../../../../public/views/recuperar_clave.php';
    }

    public function mostrarFormularioRestablecer(string $token): void {
        require_once __DIR__ . '/../../../../public/views/restablecer_clave.php';
    }

    public function restablecerClave(array $request): void {
        $token      = $request['token']      ?? '';
        $nuevaClave = $request['nueva_clave'] ?? '';
        $error      = null;

        try {
            $this->restablecerClaveUseCase->execute($token, $nuevaClave);
            header('Location: login.html?reset=1');
            exit();
        } catch (\Exception $e) {
            $error = $e->getMessage();
            require_once __DIR__ . '/../../../../public/views/restablecer_clave.php';
        }
    } 

    public function mostrarFormularioRecuperacion(): void {
        $mensaje = null;
        require_once __DIR__ . '/../../../../public/views/recuperar_clave.php';
    }

} 