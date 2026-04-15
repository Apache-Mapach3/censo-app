<?php

namespace App\Usuario\Infrastructure\Controllers;

use App\Usuario\Application\UseCase\CrearUsuarioUseCase;
use App\Usuario\Application\UseCase\AutenticarUsuarioUseCase;

class UsuarioController {
    // Inyectamos los Casos de Uso
    public function __construct(
        private CrearUsuarioUseCase $crearUsuarioUseCase,
        private AutenticarUsuarioUseCase $autenticarUsuarioUseCase
    ) {}

    // Método para manejar el POST del formulario de registro
    public function registrar(array $request): void {
        try {
            $this->crearUsuarioUseCase->execute(
                $request['nombre'],
                $request['clave'],
                $request['rol']
            );
            echo "Usuario registrado exitosamente.";
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
            // Aquí en un entorno real iniciarías variables de sesión
            // session_start(); $_SESSION['usuario_id'] = $usuario->getId();
            echo "Bienvenido, " . $usuario->getNombre() . " (" . $usuario->getRol() . ")";
        } else {
            echo "Credenciales incorrectas.";
        }
    }
}