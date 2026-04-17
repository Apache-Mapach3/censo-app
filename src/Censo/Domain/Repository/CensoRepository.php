<?php

namespace App\Censo\Domain\Repository;

use App\Censo\Domain\Model\Censo;

interface CensoRepository {
    public function save(Censo $censo, int $orgId): void;
    public function findById(int $id): ?Censo;
<<<<<<< refactor
    public function findAll(): array;
=======
    public function findAll(?int $orgId = null): array;
>>>>>>> local
    public function update(Censo $censo): void;
    public function delete(int $id): void;
}