<?php

namespace App\Usuario\Application\UseCase;

use App\Usuario\Domain\Model\Usuario;
use App\Usuario\Domain\Repository\UsuarioRepository;

class AutenticarUsuarioUseCase {

    public function __construct(private UsuarioRepository $repository) {}

    public function execute(string $nombre, string $clavePlana): ?Usuario {

        if (trim($nombre) === '' || trim($clavePlana) === '') {
            return null;
        }

        $usuario = $this->repository->findByNombre($nombre);

        if (!$usuario || !password_verify($clavePlana, $usuario->getClave())) {
            return null;
        }

        return $usuario;
    }
}