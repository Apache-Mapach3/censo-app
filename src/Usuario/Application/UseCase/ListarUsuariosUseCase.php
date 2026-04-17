<?php

namespace App\Usuario\Application\UseCase;

use App\Usuario\Domain\Repository\UsuarioRepository;

class ListarUsuariosUseCase {

    public function __construct(private UsuarioRepository $repository) {}

    public function execute(): array {
        return $this->repository->findAll();
    }
}