<?php

namespace App\Censo\Application\UseCase;

use App\Censo\Domain\Model\Censo;
use App\Censo\Domain\Repository\CensoRepository;

class ActualizarCensoUseCase {

    public function __construct(private CensoRepository $repository) {}

    public function execute(int $id, array $datos): void {

        // Obtener el censo existente para preservar campos no editables
        $censoExistente = $this->repository->findById($id);
        if (!$censoExistente) {
            throw new \InvalidArgumentException("Censo con id $id no encontrado");
        }

        $camposRequeridos = [
            'nombre', 'fecha', 'pais', 'departamento', 'ciudad', 'casa',
            'numHombres', 'numMujeres', 'numAncianosHombres', 'numAncianasMujeres',
            'numNinos', 'numNinas', 'numHabitaciones', 'numCamas', 'nombreSensador'
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
            $id,
            $censoExistente->getJefeFamilia(),
            $censoExistente->getDocumento(),
            $censoExistente->getDireccion(),
            $censoExistente->getBarrio(),
            $censoExistente->getCantidadPersonas(),
            $censoExistente->getEstrato(),
            $censoExistente->getObservaciones(),
            $censoExistente->getOrganizacionId(),
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
            (bool)($datos['tieneAgua']          ?? false),
            (bool)($datos['tieneLuz']           ?? false),
            (bool)($datos['tieneAlcantarillado'] ?? false),
            (bool)($datos['tieneGas']           ?? false),
            (bool)($datos['tieneOtrosServicios'] ?? false),
            $datos['nombreSensador']
        );

        $this->repository->update($censo);
    }
}