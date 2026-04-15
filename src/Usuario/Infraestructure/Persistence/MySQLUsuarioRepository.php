<?php

namespace App\Usuario\Infrastructure\Persistence;

use App\Usuario\Domain\Model\Usuario;
use App\Usuario\Domain\Repository\UsuarioRepository;
use PDO;

class MySQLUsuarioRepository implements UsuarioRepository {
    
    public function __construct(private PDO $connection) {}

    public function save(Usuario $usuario): void {
        $stmt = $this->connection->prepare(
            "INSERT INTO usuarios (nombre, clave, rol) VALUES (:nombre, :clave, :rol)"
        );
        $stmt->execute([
            'nombre' => $usuario->getNombre(),
            'clave'  => $usuario->getClave(),
            'rol'    => $usuario->getRol()
        ]);
    }

    public function findById(int $id): ?Usuario {
        $stmt = $this->connection->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new Usuario($row['id'], $row['nombre'], $row['clave'], $row['rol']);
    }

    /** @return Usuario[] */
    public function findAll(): array {
        $stmt = $this->connection->query("SELECT * FROM usuarios");
        $usuarios = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usuarios[] = new Usuario($row['id'], $row['nombre'], $row['clave'], $row['rol']);
        }
        return $usuarios;
    }

    public function update(Usuario $usuario): void {
        $stmt = $this->connection->prepare(
            "UPDATE usuarios SET nombre = :nombre, clave = :clave, rol = :rol WHERE id = :id"
        );
        $stmt->execute([
            'nombre' => $usuario->getNombre(),
            'clave'  => $usuario->getClave(),
            'rol'    => $usuario->getRol(),
            'id'     => $usuario->getId()
        ]);
    }

    public function delete(int $id): void {
        $stmt = $this->connection->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function findByNombre(string $nombre): ?Usuario {
        $stmt = $this->connection->prepare("SELECT * FROM usuarios WHERE nombre = :nombre");
        $stmt->execute(['nombre' => $nombre]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new Usuario($row['id'], $row['nombre'], $row['clave'], $row['rol']);
    }
}