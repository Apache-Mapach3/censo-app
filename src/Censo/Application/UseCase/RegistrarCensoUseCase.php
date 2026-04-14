<?php

namespace App\Censo\Application\UseCase;

use App\Censo\Domain\Model\Censo;
use App\Censo\Domain\Repository\CensoRepository;

class RegistrarCensoUseCase {

    public function __construct(private CensoRepository $repository) {}

    public function execute(array $datos): void {

        // Validación mínima
        if (!isset($datos['nombre'], $datos['fecha'])) {
            throw new \InvalidArgumentException("Datos incompletos");
        }

        // Conversión segura de fecha
        try {
            $fecha = new \DateTime($datos['fecha']);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException("Fecha invalida");
        }

        $censo = new Censo(
            null,
            $datos['nombre'],
            $fecha,
            $datos['pais'],
            $datos['departamento'],
            $datos['ciudad'],
            $datos['casa'],
            (int)$datos['numHombres'],
            (int)$datos['numMujeres'],
            (int)$datos['numAncianosHombres'],
            (int)$datos['numAncianasMujeres'],
            (int)$datos['numNinos'],
            (int)$datos['numNinas'],
            (int)$datos['numHabitaciones'],
            (int)$datos['numCamas'],
            (bool)$datos['tieneAgua'],
            (bool)$datos['tieneLuz'],
            (bool)$datos['tieneAlcantarillado'],
            (bool)$datos['tieneGas'],
            (bool)$datos['tieneOtrosServicios'],
            $datos['nombreSensador']
        );

        $this->repository->save($censo);
    }
}