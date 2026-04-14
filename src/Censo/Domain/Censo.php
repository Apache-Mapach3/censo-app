<?php

namespace App\Censo\Domain\Model;

class Censo {
    public function __construct(
        private ?int $id,
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
            throw new \InvalidArgumentException("Cantidad de personas inválida");
        }

        if ($this->numHabitaciones <= 0) {
            throw new \InvalidArgumentException("Debe haber al menos una habitación");
        }

        if ($this->numCamas < 0) {
            throw new \InvalidArgumentException("Número de camas inválido");
        }
    }

    public function getId(): ?int { return $this->id; }
    public function getNombre(): string { return $this->nombre; }
    public function getFecha(): \DateTime { return $this->fecha; }
    public function getPais(): string { return $this->pais; }
    public function getNombreSensador(): string { return $this->nombreSensador; }
}