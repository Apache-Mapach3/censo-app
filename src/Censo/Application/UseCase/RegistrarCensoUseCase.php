<?php

namespace App\Censo\Application\UseCase;

use App\Censo\Domain\Model\Censo;
use App\Censo\Domain\Repository\CensoRepository;

class RegistrarCensoUseCase {

    public function __construct(private CensoRepository $repository) {}

    public function execute(array $datos): void {


        $camposEstrictos = [
    'jefe_familia', 
    'documento', 
    'barrio', 
    'casa', 
    'cantidad_personas', 
    'organizacion_id'
];

foreach ($camposEstrictos as $campo) {
    if (!isset($datos[$campo]) || $datos[$campo] === null) {
        $datos[$campo] = '';
    }

}

        try {
            $fecha = new \DateTime($datos['fecha']);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException("Fecha inválida");
        }

    $jefe = $datos['jefe_familia'] ?? 'Sin nombre';
$doc  = $datos['documento'] ?? '0';
$dir  = $datos['direccion'] ?? 'Sin dirección';
$barr = $datos['barrio'] ?? 'Sin barrio';
$cant = (int)($datos['cantidad_personas'] ?? 0);
$estr = (int)($datos['estrato'] ?? 0);
$obs  = $datos['observaciones'] ?? '';


$censo = new Censo(
    null,      
    $jefe,     
    $doc,     
    $dir,         
    $barr,         
    $cant,         
    $estr,          
    $obs,            
    (int)$datos['organizacion_id'],
    $datos['nombre'],
    new \DateTime($datos['fecha']),
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
    (bool)($datos['tieneAgua'] ?? false),
    (bool)($datos['tieneLuz'] ?? false),
    (bool)($datos['tieneAlcantarillado'] ?? false),
    (bool)($datos['tieneGas'] ?? false),
    (bool)($datos['tieneOtrosServicios'] ?? false),
    $datos['nombreSensador'] ?? 'Anonimo'
);

        $this->repository->save($censo, (int)$datos['organizacion_id']);
    }
}