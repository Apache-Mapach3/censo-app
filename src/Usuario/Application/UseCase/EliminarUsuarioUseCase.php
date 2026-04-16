<?php

namespace App\Usuario\Application\UseCase;

use App\Usuario\Domain\Repository\UsuarioRepository;

class EliminarUsuarioUseCase {

    public function __construct(private UsuarioRepository $repository) {}

    public function execute(int $id): void {
        $usuario = $this->repository->findById($id);
        if (!$usuario) {
            throw new \InvalidArgumentException("Usuario con id $id no encontrado");
        }
        $this->repository->delete($id);
    }
}