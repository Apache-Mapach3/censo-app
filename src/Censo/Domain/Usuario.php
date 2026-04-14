<?php

namespace App\Usuario\Domain\Model;

class Usuario {
    public function __construct(
        private ?int $id,
        private string $nombre,
        private string $clave,
        private string $rol
    ) {
        $this->validar();
    }

    private function validar(): void {
        if (empty($nombre)) {
    throw new \InvalidArgumentException("Nombre requerido");
}

        if (empty($this->clave)) {
            throw new \InvalidArgumentException("Clave requerida");
        }

        if (empty($this->rol)) {
            throw new \InvalidArgumentException("Rol requerido");
        }
    }

    public function getId(): ?int { return $this->id; }
    public function getNombre(): string { return $this->nombre; }
    public function getClave(): string { return $this->clave; }
    public function getRol(): string { return $this->rol; }
}