<?php

namespace App\Censo\Infrastructure\Controllers;

use App\Censo\Application\UseCase\RegistrarCensoUseCase;
use Exception;

class CensoController
{
    public function __construct(private RegistrarCensoUseCase $registrarCensoUseCase) {}

    public function registrar(array $datos) 
    {
        try {
            // Procesar checkboxes (Aseguramos que los nombres coincidan exactamente)
            $datos['tieneAgua']           = isset($datos['tieneAgua']) ? 1 : 0;
            $datos['tieneLuz']            = isset($datos['tieneLuz']) ? 1 : 0;
            $datos['tieneAlcantarillado'] = isset($datos['tieneAlcantarillado']) ? 1 : 0;
            $datos['tieneGas']            = isset($datos['tieneGas']) ? 1 : 0;
            $datos['tieneOtrosServicios'] = isset($datos['tieneOtrosServicios']) ? 1 : 0;

            // Ejecutar el caso de uso
            $this->registrarCensoUseCase->execute($datos);

            // Redirigir al listado (Sin comentarios y con la función header correcta)
            header('Location: index.php?action=listar_censos');
            exit;

        } catch (\Throwable $e) { 
            echo "<div style='font-family: sans-serif; padding: 20px; background: #ffebee; border: 1px solid #f44336; border-radius: 5px; margin: 20px;'>";
            echo "<h2 style='color: #d32f2f;'>¡Te atrapé, error!</h2>";
            echo "<b>Mensaje:</b> " . $e->getMessage() . "<br><br>";
            echo "<b>Archivo:</b> " . $e->getFile() . "<br><br>";
            echo "<b>Línea:</b> " . $e->getLine() . "<br><br>";
            echo "</div>";
            die();
        }
    }
}