<?php

namespace App\Usuario\Application\UseCase;

use App\Usuario\Domain\Model\Usuario;
use App\Usuario\Domain\Repository\UsuarioRepository;

class CrearUsuarioUseCase {

    public function __construct(private UsuarioRepository $repository) {}

    public function execute(string $nombre, string $clavePlana, string $rol): void {

        if (trim($nombre) === '' || trim($clavePlana) === '' || trim($rol) === '') {
            throw new \InvalidArgumentException("Datos invalidos");
        }

        // ✅ AÑADIDO: verificar duplicado antes de intentar insertar
        if ($this->repository->existsByNombre($nombre)) {
            throw new \InvalidArgumentException("El nombre de usuario '$nombre' ya esta en uso");
        }

        $claveHash = password_hash($clavePlana, PASSWORD_DEFAULT);
        $usuario = new Usuario(null, $nombre, $claveHash, $rol);
        $this->repository->save($usuario);
    }
}