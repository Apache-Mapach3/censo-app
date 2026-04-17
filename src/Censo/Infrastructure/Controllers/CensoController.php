<?php

namespace App\Censo\Infrastructure\Controllers;

use App\Censo\Application\UseCase\RegistrarCensoUseCase;
use App\Censo\Application\UseCase\ActualizarCensoUseCase;
use Throwable;

class CensoController
{
    public function __construct(
        private RegistrarCensoUseCase $registrarCensoUseCase,
        private ActualizarCensoUseCase $actualizarCensoUseCase
    ) {}

    public function registrar(array $request): void 
    {
        $organizacionId = $_SESSION['organizacion_id'] ?? null;

        if (!$organizacionId) {
            die("Error: Sesión no encontrada.");
        }

        try {
            $datosParaCasoDeUso = [
            'jefe_familia'      => $request['jefe_familia'] ?? '',
            'documento'         => $request['documento'] ?? '',
            'direccion'         => $request['direccion'] ?? '',
            'barrio'            => $request['barrio'] ?? '',
            'cantidad_personas' => (int)($request['cantidad_personas'] ?? 0),
            'estrato'           => (int)($request['estrato'] ?? 0),
            'observaciones'     => $request['observaciones'] ?? '',
            'organizacion_id'   => (int)$organizacionId,
            'nombre'            => $request['nombre'] ?? '', 
            'fecha'             => $request['fecha'] ?? date('Y-m-d'),
            'pais'              => $request['pais'] ?? '',
            'departamento'      => $request['departamento'] ?? '',
            'ciudad'            => $request['ciudad'] ?? '',
            'casa'              => $request['casa'] ?? '',
            'numHombres'        => (int)($request['numHombres'] ?? 0),
            'numMujeres'        => (int)($request['numMujeres'] ?? 0),
            'numAncianosHombres'=> (int)($request['numAncianosHombres'] ?? 0),
            'numAncianasMujeres'=> (int)($request['numAncianasMujeres'] ?? 0),
            'numNinos'          => (int)($request['numNinos'] ?? 0),
            'numNinas'          => (int)($request['numNinas'] ?? 0),
            'numHabitaciones'   => (int)($request['numHabitaciones'] ?? 1),
            'numCamas'          => (int)($request['numCamas'] ?? 0),
            'tieneAgua'         => isset($request['tieneAgua']),
            'tieneLuz'          => isset($request['tieneLuz']),
            'tieneAlcantarillado'=> isset($request['tieneAlcantarillado']),
            'tieneGas'          => isset($request['tieneGas']),
            'tieneOtrosServicios'=> isset($request['tieneOtrosServicios']),
            'nombreSensador'    => $request['nombreSensador'] ?? $_SESSION['usuario_nombre'] ?? 'Anonimo'
            ];

            $this->registrarCensoUseCase->execute($datosParaCasoDeUso);

            header("Location: index.php?action=listar_censos");
            exit();

        } catch (Throwable $e) {

            $this->mostrarErrorVisual($e, "Registrar Censo");
        }
    }

    public function actualizar(array $request): void 
    {
        try {
            $request['tieneAgua']           = isset($request['tieneAgua']) ? 1 : 0;
            $request['tieneLuz']            = isset($request['tieneLuz']) ? 1 : 0;
            $request['tieneAlcantarillado'] = isset($request['tieneAlcantarillado']) ? 1 : 0;
            $request['tieneGas']            = isset($request['tieneGas']) ? 1 : 0;
            $request['tieneOtrosServicios'] = isset($request['tieneOtrosServicios']) ? 1 : 0;

            $this->actualizarCensoUseCase->execute($request);

            header('Location: index.php?action=listar_censos');
            exit;
        } catch (Throwable $e) {
            $this->mostrarErrorVisual($e, "Actualizar Censo");
        }
    }


    private function mostrarErrorVisual(Throwable $e, string $accion): void 
    {
        echo "<div style='font-family:sans-serif;padding:20px;background:#ffebee;border:1px solid #f44336;margin:20px;'>";
        echo "<h2 style='color:#d32f2f;'>❌ Error en: $accion</h2>";
        echo "<p><b>Mensaje:</b> " . $e->getMessage() . "</p>";
        echo "<hr><small>Archivo: " . $e->getFile() . " Línea: " . $e->getLine() . "</small>";
        echo "</div>";
        die();
    }
} 