<?php

namespace App\Censo\Application\UseCase;

use App\Censo\Domain\Model\Censo;
use App\Censo\Domain\Repository\CensoRepository;

class RegistrarCensoUseCase {

    public function __construct(private CensoRepository $repository) {}

    public function execute(array $datos): void {

        try {
            $fecha = new \DateTime($datos['fecha']);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException("Fecha inválida");
        }

        $censo = new Censo(
            null,
            $datos['jefe_familia']      ?? '',
            $datos['documento']         ?? '',
            $datos['direccion']         ?? '',
            $datos['barrio']            ?? '',
            (int)($datos['cantidad_personas'] ?? 0),
            isset($datos['estrato']) && $datos['estrato'] !== '' ? (int)$datos['estrato'] : null,
            $datos['observaciones']     ?? null,
            (int)($datos['organizacion_id'] ?? 0),
            $datos['nombre']            ?? '',
            $fecha,
            $datos['pais']              ?? '',
            $datos['departamento']      ?? '',
            $datos['ciudad']            ?? '',
            $datos['casa']              ?? '',
            (int)($datos['numHombres']          ?? 0),
            (int)($datos['numMujeres']          ?? 0),
            (int)($datos['numAncianosHombres']  ?? 0),
            (int)($datos['numAncianasMujeres']  ?? 0),
            (int)($datos['numNinos']            ?? 0),
            (int)($datos['numNinas']            ?? 0),
            (int)($datos['numHabitaciones']     ?? 1),
            (int)($datos['numCamas']            ?? 0),
            (bool)($datos['tieneAgua']          ?? false),
            (bool)($datos['tieneLuz']           ?? false),
            (bool)($datos['tieneAlcantarillado'] ?? false),
            (bool)($datos['tieneGas']           ?? false),
            (bool)($datos['tieneOtrosServicios'] ?? false),
            $datos['nombreSensador']    ?? 'Anonimo'
        );

        $this->repository->save($censo);
    }
}