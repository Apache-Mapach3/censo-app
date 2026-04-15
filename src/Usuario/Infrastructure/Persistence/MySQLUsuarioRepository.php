<?php

namespace App\Usuario\Infrastructure\Persistence;

use App\Usuario\Domain\Model\Usuario;
use App\Usuario\Domain\Repository\UsuarioRepository;
use PDO;

class MySQLUsuarioRepository implements UsuarioRepository {
    
    public function __construct(private PDO $pdo) {}

    public function save(Usuario $usuario): void {
        $stmt = $this->pdo->prepare(
            "INSERT INTO usuario (nombre, clave, rol) VALUES (?, ?, ?)"
        );
        $stmt->execute([
            $usuario->getNombre(),
            $usuario->getClave(),
            $usuario->getRol()
        ]);
    }

    public function findById(int $id): ?Usuario {
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return new Usuario($row['id'], $row['nombre'], $row['clave'], $row['rol']);
    }

    /** @return Usuario[] */
    public function findAll(): array {
     
        $stmt = $this->pdo->query("SELECT * FROM usuario");
        $usuario = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usuario[] = new Usuario($row['id'], $row['nombre'], $row['clave'], $row['rol']);
        }
        return $usuario;
    }

    public function update(Usuario $usuario): void {
      
        $stmt = $this->pdo->prepare(
            "UPDATE usuario SET nombre = :nombre, clave = :clave, rol = :rol WHERE id = :id"
        );
        $stmt->execute([
            'nombre' => $usuario->getNombre(),
            'clave'  => $usuario->getClave(),
            'rol'    => $usuario->getRol(),
            'id'     => $usuario->getId()
        ]);
    }

    public function delete(int $id): void {
    
        $stmt = $this->pdo->prepare("DELETE FROM usuario WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }


    public function findByNombre(string $nombre): ?Usuario {
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE nombre = ?");
        $stmt->execute([$nombre]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) return null;

        return new Usuario($data['id'], $data['nombre'], $data['clave'], $data['rol']);
    }

    public function existsByNombre(string $nombre): bool {
    $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM usuario WHERE nombre = ?");
    $stmt->execute([$nombre]);
    return (int)$stmt->fetchColumn() > 0;
}
}