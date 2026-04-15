<?php

namespace App\Usuario\Infrastructure\Controllers;

use App\Usuario\Application\UseCase\CrearUsuarioUseCase;
use App\Usuario\Application\UseCase\AutenticarUsuarioUseCase;

class UsuarioController {
    public function __construct(
        private CrearUsuarioUseCase $crearUsuarioUseCase,
        private AutenticarUsuarioUseCase $autenticarUsuarioUseCase
    ) {}

    // Método para manejar el POST del formulario de registro
    public function registrar(array $request): void {
        try {
            $this->crearUsuarioUseCase->execute(
                $request['nombre'],
                $request['clave']
            );
            
            // Mensaje aplanado en una sola línea para evitar errores de sintaxis
            echo "<script>alert('Usuario registrado exitosamente. Ahora puedes iniciar sesión.'); window.location.href='login.html';</script>";
            exit();

        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Método para manejar el POST del formulario de login
    public function login(array $request): void {
        $usuario = $this->autenticarUsuarioUseCase->execute(
            $request['nombre'],
            $request['clave']
        );

        if ($usuario) {
            // 1. Curar la amnesia: Encender la memoria de PHP
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            // 2. Ponerle la "pulsera" al usuario guardando sus datos en la sesión
            $_SESSION['usuario_id'] = $usuario->getId();
            $_SESSION['usuario_nombre'] = $usuario->getNombre();
            
            // 3. Arreglar el GPS: Redirigir al listado de censos
            header("Location: index.php?action=listar_censos");
            exit();

        } else {
            // Mensaje aplanado en una sola línea
            echo "<script>alert('Credenciales incorrectas. Intenta de nuevo.'); window.location.href='login.html';</script>";
            exit();
        }
    }
}