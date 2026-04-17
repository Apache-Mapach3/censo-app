<?php

namespace App\Usuario\Application\UseCase;

use App\Usuario\Domain\Model\Usuario;
use App\Usuario\Domain\Repository\UsuarioRepository;

class AutenticarUsuarioUseCase {

    public function __construct(private UsuarioRepository $repository) {}

    public function execute(string $correo, string $clavePlana): ?Usuario {

        if (trim($correo) === '' || trim($clavePlana) === '') {
            die("DEBUG: El correo o la clave llegaron vacíos desde el formulario.");
        }

        $usuario = $this->repository->findByCorreo($correo);

        if (!$usuario) {
            die("DEBUG: Busqué en la base de datos pero NO encontré este correo: " . $correo);
        }

        if (!password_verify($clavePlana, $usuario->getClave())) {
            die("DEBUG: El correo existe, pero la contraseña NO coincide.");
        }

        return $usuario;
    }

} 