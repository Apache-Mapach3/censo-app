<?php

namespace App\Usuario\Infrastructure\Controllers;

use App\Usuario\Application\UseCase\CrearUsuarioUseCase;
use App\Usuario\Application\UseCase\AutenticarUsuarioUseCase;

/**
 * BUG CORREGIDO:
 *  - Existían dos archivos duplicados: uno en Infraestructure/ (typo de directorio)
 *    y otro en Infrastructure/. Se elimina el typo y se usa sólo Infrastructure/.
 *  - Se activa session_start() y $_SESSION para que el login funcione realmente.
 *  - Se redirige correctamente tras registrar/loguear en vez de sólo hacer echo.
 */
class UsuarioController
{
    public function __construct(
        private CrearUsuarioUseCase      $crearUsuarioUseCase,
        private AutenticarUsuarioUseCase $autenticarUsuarioUseCase
    ) {}

    public function registrar(array $request): void
    {
        try {
            $this->crearUsuarioUseCase->execute(
                $request['nombre'] ?? '',
                $request['clave']  ?? '',
                $request['rol']    ?? ''
            );
            // Redirige al login con mensaje de éxito
            header("Location: login.html?registered=1");
            exit();
        } catch (\Exception $e) {
            // Muestra error en la misma pantalla de registro
            echo "<p style='color:red;font-family:sans-serif;padding:20px'>Error: "
                . htmlspecialchars($e->getMessage())
                . " <a href='registro.html'>Volver</a></p>";
        }
    }

    public function login(array $request): void
    {
        $usuario = $this->autenticarUsuarioUseCase->execute(
            $request['nombre'] ?? '',
            $request['clave']  ?? ''
        );

        if ($usuario) {
            // Guardamos datos en sesión
            $_SESSION['usuario_id']     = $usuario->getId();
            $_SESSION['usuario_nombre'] = $usuario->getNombre();
            $_SESSION['usuario_rol']    = $usuario->getRol();

            // Redirige al listado de censos (página principal tras login)
            header("Location: index.php?action=listar_censos");
            exit();
        } else {
            echo "<p style='color:red;font-family:sans-serif;padding:20px'>Credenciales incorrectas. "
                . "<a href='login.html'>Volver</a></p>";
        }
    }
}