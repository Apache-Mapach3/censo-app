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
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }

    public function actualizar(array $request): void {
        try {
            $id = (int)($request['id'] ?? 0);
            if (!$id) {
                throw new \InvalidArgumentException("ID de censo inválido");
            }

            // Normalizar checkboxes: si no vienen en POST son false
            $request['tieneAgua']           = isset($request['tieneAgua'])           ? 1 : 0;
            $request['tieneLuz']            = isset($request['tieneLuz'])            ? 1 : 0;
            $request['tieneAlcantarillado'] = isset($request['tieneAlcantarillado']) ? 1 : 0;
            $request['tieneGas']            = isset($request['tieneGas'])            ? 1 : 0;
            $request['tieneOtrosServicios'] = isset($request['tieneOtrosServicios']) ? 1 : 0;

            // FIX: execute recibe (int $id, array $datos) — no un solo array
            $this->actualizarUseCase->execute($id, $request);

            header('Location: index.php?action=listar_censos');
            exit;
        } catch (\InvalidArgumentException $e) {
            echo "Error de validacion: " . htmlspecialchars($e->getMessage());
        } catch (\Exception $e) {
            echo "Error del sistema: " . htmlspecialchars($e->getMessage());
        }
    }

    public function eliminar(int $id): void {
        try {
            $this->eliminarUseCase->execute($id);
            header('Location: index.php?action=listar_censos');
            exit;
        } catch (\InvalidArgumentException $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }
}