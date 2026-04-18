<?php

namespace App\Censo\Infrastructure\Controllers;

use App\Censo\Application\UseCase\RegistrarCensoUseCase;
use App\Censo\Application\UseCase\ActualizarCensoUseCase;
use Throwable;

class CensoController
{
    public function __construct(
        private RegistrarCensoUseCase  $registrarCensoUseCase,
        private ActualizarCensoUseCase $actualizarCensoUseCase
    ) {}

    public function registrar(array $request, int $orgId = 0): void
    {
        if (!$orgId) {
            $orgId = (int)($_SESSION['organizacion_id'] ?? 0);
        }

        if (!$orgId) {
            die("Error: Sesión no encontrada o sin organización asignada.");
        }

        try {
            $datos = [
                'jefe_familia'       => $request['jefe_familia']       ?? '',
                'documento'          => $request['documento']          ?? '',
                'direccion'          => $request['direccion']          ?? '',
                'barrio'             => $request['barrio']             ?? '',
                'cantidad_personas'  => (int)($request['cantidad_personas'] ?? 0),
                'estrato'            => $request['estrato']            ?? '',
                'observaciones'      => $request['observaciones']      ?? '',
                'organizacion_id'    => $orgId,
                'nombre'             => $request['nombre']             ?? '',
                'fecha'              => $request['fecha']              ?? date('Y-m-d'),
                'pais'               => $request['pais']               ?? '',
                'departamento'       => $request['departamento']       ?? '',
                'ciudad'             => $request['ciudad']             ?? '',
                'casa'               => $request['casa']               ?? '',
                'numHombres'         => (int)($request['numHombres']          ?? 0),
                'numMujeres'         => (int)($request['numMujeres']          ?? 0),
                'numAncianosHombres' => (int)($request['numAncianosHombres']  ?? 0),
                'numAncianasMujeres' => (int)($request['numAncianasMujeres']  ?? 0),
                'numNinos'           => (int)($request['numNinos']            ?? 0),
                'numNinas'           => (int)($request['numNinas']            ?? 0),
                'numHabitaciones'    => (int)($request['numHabitaciones']     ?? 1),
                'numCamas'           => (int)($request['numCamas']            ?? 0),
                'tieneAgua'          => isset($request['tieneAgua']),
                'tieneLuz'           => isset($request['tieneLuz']),
                'tieneAlcantarillado'=> isset($request['tieneAlcantarillado']),
                'tieneGas'           => isset($request['tieneGas']),
                'tieneOtrosServicios'=> isset($request['tieneOtrosServicios']),
                'nombreSensador'     => $request['nombreSensador'] ?? ($_SESSION['usuario_nombre'] ?? 'Anonimo'),
            ];

            $this->registrarCensoUseCase->execute($datos);

            header("Location: index.php?action=listar_censos");
            exit();

        } catch (Throwable $e) {
            $this->mostrarErrorVisual($e, "Registrar Censo");
        }
    }

    public function actualizar(array $request): void
    {
        try {
            $id = (int)($request['id'] ?? 0);
            if (!$id) {
                throw new \InvalidArgumentException("ID de censo inválido");
            }

            // Normalizar checkboxes: si no vienen en el POST su valor es false
            $request['tieneAgua']           = isset($request['tieneAgua'])           ? 1 : 0;
            $request['tieneLuz']            = isset($request['tieneLuz'])            ? 1 : 0;
            $request['tieneAlcantarillado'] = isset($request['tieneAlcantarillado']) ? 1 : 0;
            $request['tieneGas']            = isset($request['tieneGas'])            ? 1 : 0;
            $request['tieneOtrosServicios'] = isset($request['tieneOtrosServicios']) ? 1 : 0;

            $this->actualizarCensoUseCase->execute($id, $request);

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
        echo "<p><b>Mensaje:</b> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<hr><small>Archivo: " . $e->getFile() . " Línea: " . $e->getLine() . "</small>";
        echo "</div>";
        exit();
    }
}