<?php

namespace App\Censo\Infrastructure\Controllers;

use App\Censo\Application\UseCase\ListarCensosUseCase;

class ListarCensosController {

    public function __construct(private ListarCensosUseCase $listarCensosUseCase) {}

    public function listar(): void {
        $censos = $this->listarCensosUseCase->execute();
        // Pasamos los datos a la vista
        require_once __DIR__ . '/../../../../public/views/listar_censos.php';
    }
}