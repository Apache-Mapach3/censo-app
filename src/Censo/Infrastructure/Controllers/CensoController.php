<?php

namespace App\Censo\Infrastructure\Controllers;

use App\Censo\Application\UseCase\RegistrarCensoUseCase;

class CensoController {
    
    public function __construct(private RegistrarCensoUseCase $registrarCensoUseCase) {}

    // Método para manejar el POST del formulario del censo
    public function registrar(array $request): void {
        try {
            $this->registrarCensoUseCase->execute($request);
            echo "Censo registrado correctamente.";
        } catch (\InvalidArgumentException $e) {
            // Capturamos los errores de validación de nuestras entidades DDD
            echo "Error de validación: " . $e->getMessage();
        } catch (\Exception $e) {
            echo "Error del sistema: " . $e->getMessage();
        }
    }
}