<?php

namespace App\Organizacion\Domain\Model;

class Organizacion {
    public function __construct(
        private ?int   $id,
        private string $nombre,
        private string $codigo   // slug único: "equipo_jose_2024"
    ) {
        if (empty($nombre)) throw new \InvalidArgumentException("Nombre requerido");
        if (empty($codigo))  throw new \InvalidArgumentException("Código requerido");
    }

    public function getId(): ?int    { return $this->id; }
    public function getNombre(): string { return $this->nombre; }
    public function getCodigo(): string  { return $this->codigo; }
}