<?php

namespace App\Censo\Domain\Model;

class Censo {
    public function __construct(
    private ?int $id,
    private string $jefe_familia,
    private string $documento,
    private string $direccion,
    private string $barrio,
    private int $cantidad_personas,
    private ?int $estrato,
    private ?string $observaciones,
    private int $organizacion_id,
    private string $nombre,
    private \DateTime $fecha,
    private string $pais,
    private string $departamento,
    private string $ciudad,
    private string $casa,
    private int $numHombres,
    private int $numMujeres,
    private int $numAncianosHombres,
    private int $numAncianasMujeres,
    private int $numNinos,
    private int $numNinas,
    private int $numHabitaciones,
    private int $numCamas,
    private bool $tieneAgua,
    private bool $tieneLuz,
    private bool $tieneAlcantarillado,
    private bool $tieneGas,
    private bool $tieneOtrosServicios,
    private string $nombreSensador
) {
    $this->validar();
    }

    private function validar(): void {
        if ($this->numHombres < 0 || $this->numMujeres < 0) {
            throw new \InvalidArgumentException("Cantidad de personas invalida");
        }
        if ($this->numHabitaciones <= 0) {
            throw new \InvalidArgumentException("Debe haber al menos una habitacion");
        }
        if ($this->numCamas < 0) {
            throw new \InvalidArgumentException("Numero de camas invalido");
        }
    }

   
    public function getId(): ?int { return $this->id; }
    public function getNombre(): string { return $this->nombre; }
    public function getFecha(): \DateTime { return $this->fecha; }
    public function getPais(): string { return $this->pais; }
    public function getNombreSensador(): string { return $this->nombreSensador; }


    public function getDepartamento(): string { return $this->departamento; }
    public function getCiudad(): string { return $this->ciudad; }
    public function getCasa(): string { return $this->casa; }
    public function getNumHombres(): int { return $this->numHombres; }
    public function getNumMujeres(): int { return $this->numMujeres; }
    public function getNumAncianosHombres(): int { return $this->numAncianosHombres; }
    public function getNumAncianasMujeres(): int { return $this->numAncianasMujeres; }
    public function getNumNinos(): int { return $this->numNinos; }
    public function getNumNinas(): int { return $this->numNinas; }
    public function getNumHabitaciones(): int { return $this->numHabitaciones; }
    public function getNumCamas(): int { return $this->numCamas; }
    public function getTieneAgua(): bool { return $this->tieneAgua; }
    public function getTieneLuz(): bool { return $this->tieneLuz; }
    public function getTieneAlcantarillado(): bool { return $this->tieneAlcantarillado; }
    public function getTieneGas(): bool { return $this->tieneGas; }
    public function getTieneOtrosServicios(): bool { return $this->tieneOtrosServicios; }
}