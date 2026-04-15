<?php

namespace App\Censo\Application\UseCase;

use App\Censo\Domain\Repository\CensoRepository;

class EliminarCensoUseCase {

    public function __construct(private CensoRepository $repository) {}

    public function execute(int $id): void {
        $censo = $this->repository->findById($id);

        if (!$censo) {
            throw new \InvalidArgumentException("Censo con id $id no existe");
        }

        $this->repository->delete($id);
    }
}