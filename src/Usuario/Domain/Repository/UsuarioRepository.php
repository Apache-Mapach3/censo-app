<?php

namespace App\Usuario\Domain\Repository;

use App\Usuario\Domain\Model\Usuario;

interface UsuarioRepository
{
    public function save(Usuario $usuario): void;

    public function update(Usuario $usuario): void;

    public function findById(int $id): ?Usuario;

    /** @return Usuario[] */
    public function findAll(): array;

    public function delete(int $id): void;

    // Método clave para autenticación
    public function findByNombre(string $nombre): ?Usuario;
}