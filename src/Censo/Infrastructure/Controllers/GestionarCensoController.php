<?php

namespace App\Censo\Infrastructure\Controllers;

use App\Censo\Application\UseCase\ObtenerCensoPorIdUseCase;
use App\Censo\Application\UseCase\ActualizarCensoUseCase;
use App\Censo\Application\UseCase\EliminarCensoUseCase;

class GestionarCensoController {

    public function __construct(
        private ObtenerCensoPorIdUseCase $obtenerUseCase,
        private ActualizarCensoUseCase   $actualizarUseCase,
        private EliminarCensoUseCase     $eliminarUseCase
    ) {}

    public function cargarFormularioEdicion(int $id): void {
        try {
            $censo = $this->obtenerUseCase->execute($id);
            require_once __DIR__ . '/../../../../public/views/editar_censo.php';
        } catch (\InvalidArgumentException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function actualizar(array $request): void {
        try {
            $this->actualizarUseCase->execute((int)$request['id'], $request);
            header('Location: index.php?action=listar_censos');
            exit;
        } catch (\InvalidArgumentException $e) {
            echo "Error de validacion: " . $e->getMessage();
        } catch (\Exception $e) {
            echo "Error del sistema: " . $e->getMessage();
        }
    }

    public function eliminar(int $id): void {
        try {
            $this->eliminarUseCase->execute($id);
            header('Location: index.php?action=listar_censos');
            exit;
        } catch (\InvalidArgumentException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}