<?php

namespace App\Usuario\Domain\Repository;

interface TokenRecuperacionRepository {
    public function guardar(int $usuarioId, string $token, \DateTime $expira): void;
    public function findByToken(string $token): ?array;
    public function marcarUsado(string $token): void;
    public function eliminarExpirados(): void;
}