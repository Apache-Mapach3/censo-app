<?php

namespace App\Organizacion\Infrastructure\Persistence;

use App\Organizacion\Domain\Model\Organizacion;
use App\Organizacion\Domain\Repository\OrganizacionRepository;
use PDO;

class MySQLOrganizacionRepository implements OrganizacionRepository {

    public function __construct(private PDO $pdo) {}

    public function save(Organizacion $organizacion): int 
    {
        $sql = "INSERT INTO organizaciones (nombre) VALUES (:nombre)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $organizacion->getNombre()
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function findById(int $id): ?Organizacion {
        $stmt = $this->pdo->prepare("SELECT * FROM organizaciones WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Organizacion((int)$row['id'], $row['nombre'], $row['codigo']) : null;
    }

    public function findByCodigo(string $codigo): ?Organizacion {
        $stmt = $this->pdo->prepare("SELECT * FROM organizaciones WHERE codigo = ?");
        $stmt->execute([$codigo]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Organizacion((int)$row['id'], $row['nombre'], $row['codigo']) : null;
    }

    public function findByNombre(string $nombre): ?int
    {
        $sql = "SELECT id FROM organizaciones WHERE nombre = :nombre LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':nombre' => trim($nombre)]);
        $row = $stmt->fetch();
        
        return $row ? (int) $row['id'] : null;
    }
}