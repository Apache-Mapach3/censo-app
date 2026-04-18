<?php

namespace App\Censo\Infrastructure\Controllers;

use App\Censo\Application\UseCase\ListarCensosUseCase;

class ListarCensosController {

    public function __construct(private ListarCensosUseCase $listarCensosUseCase) {}

    public function listar(?int $orgId = null): void {
        $censos = $this->listarCensosUseCase->execute($orgId);
        require_once __DIR__ . '/../../../../public/views/listar_censos.php';
    }
}