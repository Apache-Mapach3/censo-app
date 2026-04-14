<?php

namespace App\Usuario\Application\UseCase;

use App\Usuario\Domain\Model\Usuario;
use App\Usuario\Domain\Repository\UsuarioRepository;

class CrearUsuarioUseCase {

    public function __construct(private UsuarioRepository $repository) {}

    public function execute(string $nombre, string $clavePlana, string $rol): void {

        // Validaciones basicas
        if (trim($nombre) === '' || trim($clavePlana) === '' || trim($rol) === '') {
            throw new \InvalidArgumentException("Datos invalidos");
        }

        // Hash seguro de contraseña
        $claveHash = password_hash($clavePlana, PASSWORD_DEFAULT);

        $usuario = new Usuario(null, $nombre, $claveHash, $rol);

        $this->repository->save($usuario);
    }
}