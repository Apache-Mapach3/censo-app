<?php

namespace App\Organizacion\Infrastructure\Persistence;

use App\Organizacion\Domain\Model\Organizacion;
use App\Organizacion\Domain\Repository\OrganizacionRepository;
use PDO;

class MySQLOrganizacionRepository implements OrganizacionRepository {

    public function __construct(private PDO $pdo) {}

    public function save(Organizacion $org): int {
        $stmt = $this->pdo->prepare(
            "INSERT INTO organizaciones (nombre, codigo) VALUES (?, ?)"
        );
        $stmt->execute([$org->getNombre(), $org->getCodigo()]);
        return (int)$this->pdo->lastInsertId();
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
}