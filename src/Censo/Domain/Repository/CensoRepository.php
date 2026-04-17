<?php

namespace App\Censo\Domain\Repository;

use App\Censo\Domain\Model\Censo;

interface CensoRepository {
    public function save(Censo $censo): void;
    public function findById(int $id): ?Censo;
    public function findAll(int $orgId): array;
    public function update(Censo $censo): void;
    public function delete(int $id): void;
}