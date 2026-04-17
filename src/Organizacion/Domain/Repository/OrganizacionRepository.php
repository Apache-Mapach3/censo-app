<?php
namespace App\Organizacion\Domain\Repository;

use App\Organizacion\Domain\Model\Organizacion;

interface OrganizacionRepository {
    public function save(Organizacion $org): int;   // retorna el id generado
    public function findById(int $id): ?Organizacion;
    public function findByCodigo(string $codigo): ?Organizacion;
}