<?php

namespace App\Censo\Application\UseCase;

use App\Censo\Domain\Model\Censo;
use App\Censo\Domain\Repository\CensoRepository;

class RegistrarCensoUseCase {

    public function __construct(private CensoRepository $repository) {}

    public function execute(array $datos): void {

        // Solo valida los campos de texto obligatorios (que no son checkboxes)
        $camposEstrictos = [
            'nombre', 'fecha', 'pais', 'departamento', 'ciudad', 'casa', 'nombreSensador'
        ];

        foreach ($camposEstrictos as $campo) {
            // Usamos empty() para que tampoco pasen campos en blanco
            if (empty($datos[$campo])) {
                throw new \InvalidArgumentException("Campo requerido faltante: $campo");
            }
        }

        try {
            $fecha = new \DateTime($datos['fecha']);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException("Fecha invalida");
        }

        // Se crea el Censo usando '??' para asignar valores por defecto (0 o false)
        // a los campos que el usuario deje vacíos o desmarcados.
        $censo = new Censo(
            null,
            $datos['nombre'],
            $fecha,
            $datos['pais'],
            $datos['departamento'],
            $datos['ciudad'],
            $datos['casa'],
            (int)($datos['numHombres'] ?? 0),
            (int)($datos['numMujeres'] ?? 0),
            (int)($datos['numAncianosHombres'] ?? 0),
            (int)($datos['numAncianasMujeres'] ?? 0),
            (int)($datos['numNinos'] ?? 0),
            (int)($datos['numNinas'] ?? 0),
            (int)($datos['numHabitaciones'] ?? 1), // Mínimo 1 habitación
            (int)($datos['numCamas'] ?? 0),
            isset($datos['tieneAgua']) ? (bool)$datos['tieneAgua'] : false,
            isset($datos['tieneLuz']) ? (bool)$datos['tieneLuz'] : false,
            isset($datos['tieneAlcantarillado']) ? (bool)$datos['tieneAlcantarillado'] : false,
            isset($datos['tieneGas']) ? (bool)$datos['tieneGas'] : false,
            isset($datos['tieneOtrosServicios']) ? (bool)$datos['tieneOtrosServicios'] : false,
            $datos['nombreSensador']
        );

        // Lo guarda en la base de datos
        $this->repository->save($censo);
    }
}