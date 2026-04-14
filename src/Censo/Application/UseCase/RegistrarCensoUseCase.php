<?php

namespace App\Censo\Application\UseCase;

use App\Censo\Domain\Model\Censo;
use App\Censo\Domain\Repository\CensoRepository;

class RegistrarCensoUseCase {

    public function __construct(private CensoRepository $repository) {}

    public function execute(array $datos): void {

    
        $camposRequeridos = [
            'nombre', 'fecha', 'pais', 'departamento', 'ciudad', 'casa',
            'numHombres', 'numMujeres', 'numAncianosHombres', 'numAncianasMujeres',
            'numNinos', 'numNinas', 'numHabitaciones', 'numCamas',
            'tieneAgua', 'tieneLuz', 'tieneAlcantarillado', 'tieneGas',
            'tieneOtrosServicios', 'nombreSensador'
        ];

        foreach ($camposRequeridos as $campo) {
            if (!isset($datos[$campo])) {
                throw new \InvalidArgumentException("Campo requerido faltante: $campo");
            }
        }

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