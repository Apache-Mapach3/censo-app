<?php

namespace App\Usuario\Infrastructure\Persistence;

use App\Usuario\Domain\Model\Usuario;
use App\Usuario\Domain\Repository\UsuarioRepository;
use PDO;

/**
 * Implementación MySQL del repositorio de usuarios.
 *
 * BUGS CORREGIDOS:
 *  - Existían dos versiones del archivo: una en /Infraestructure (typo)
 *    y otra en /Infrastructure. Se consolida aquí con el namespace correcto.
 *  - Se añade existsByNombre() que faltaba en la versión de Infrastructure.
 *  - Se usa el nombre de tabla 'usuarios' de forma consistente.
 */
class MySQLUsuarioRepository implements UsuarioRepository
{
    public function __construct(private PDO $pdo) {}

    public function save(Usuario $usuario): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO usuarios (nombre, clave, rol) VALUES (?, ?, ?)"
        );
        $stmt->execute([
            $usuario->getNombre(),
            $usuario->getClave(),
            $usuario->getRol(),
        ]);
    }

    public function findById(int $id): ?Usuario
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? new Usuario($row['id'], $row['nombre'], $row['clave'], $row['rol']) : null;
    }

    /** @return Usuario[] */
    public function findAll(): array
    {
        $stmt     = $this->pdo->query("SELECT * FROM usuarios");
        $usuarios = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usuarios[] = new Usuario($row['id'], $row['nombre'], $row['clave'], $row['rol']);
        }
        return $usuarios;
    }

    public function update(Usuario $usuario): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE usuarios SET nombre = :nombre, clave = :clave, rol = :rol WHERE id = :id"
        );
        $stmt->execute([
            'nombre' => $usuario->getNombre(),
            'clave'  => $usuario->getClave(),
            'rol'    => $usuario->getRol(),
            'id'     => $usuario->getId(),
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function findByNombre(string $nombre): ?Usuario
    {
        // 1. Usamos 'usuarios' (plural) para ser consistentes con toda la clase
        // 2. Usamos '?' para que coincida con el array posicional
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE nombre = ?");
        
        // 3. Pasamos el valor dentro de un array para el placeholder '?'
        $stmt->execute([$nombre]);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? new Usuario($row['id'], $row['nombre'], $row['clave'], $row['rol']) : null;
    }

    // BUG CORREGIDO: este método faltaba en la versión Infrastructure/
    public function existsByNombre(string $nombre): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE nombre = ?");
        $stmt->execute([$nombre]);
        return (int) $stmt->fetchColumn() > 0;
    }
}