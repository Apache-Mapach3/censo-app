<?php

namespace App\Usuario\Infrastructure\Persistence;

use App\Usuario\Domain\Model\Usuario;
use App\Usuario\Domain\Repository\UsuarioRepository;
use PDO;

class MySQLUsuarioRepository implements UsuarioRepository {

    public function __construct(private PDO $pdo) {}

    public function save(Usuario $usuario): void {
        $stmt = $this->pdo->prepare(
            "INSERT INTO usuarios (nombre, correo, clave, rol) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([
            $usuario->getNombre(),
            $usuario->getCorreo(),
            $usuario->getClave(),
            $usuario->getRol(),
        ]);
    }

    public function findById(int $id): ?Usuario {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->rowToUsuario($row) : null;
    }

    /** @return Usuario[] */
    public function findAll(): array {
        $stmt     = $this->pdo->query("SELECT * FROM usuarios ORDER BY id DESC");
        $usuarios = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usuarios[] = $this->rowToUsuario($row);
        }
        return $usuarios;
    }

    public function update(Usuario $usuario): void {
        $stmt = $this->pdo->prepare(
            "UPDATE usuarios SET nombre = :nombre, correo = :correo, clave = :clave, rol = :rol WHERE id = :id"
        );
        $stmt->execute([
            'nombre' => $usuario->getNombre(),
            'correo' => $usuario->getCorreo(),
            'clave'  => $usuario->getClave(),
            'rol'    => $usuario->getRol(),
            'id'     => $usuario->getId(),
        ]);
    }

    public function delete(int $id): void {
        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function findByNombre(string $nombre): ?Usuario {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE nombre = ?");
        $stmt->execute([$nombre]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->rowToUsuario($row) : null;
    }

    public function findByCorreo(string $correo): ?Usuario {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
        $stmt->execute([$correo]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->rowToUsuario($row) : null;
    }

    public function existsByNombre(string $nombre): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE nombre = ?");
        $stmt->execute([$nombre]);
        return (int)$stmt->fetchColumn() > 0;
    }

    private function rowToUsuario(array $row): Usuario {
        return new Usuario(
            (int)$row['id'],
            $row['nombre'],
            $row['clave'],
            $row['rol'],
            $row['correo'] ?? ''
        );
    }
}