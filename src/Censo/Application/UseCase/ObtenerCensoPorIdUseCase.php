<?php

namespace App\Censo\Application\UseCase;

use App\Censo\Domain\Model\Censo;
use App\Censo\Domain\Repository\CensoRepository;

class ObtenerCensoPorIdUseCase {

    public function __construct(private CensoRepository $repository) {}

    public function execute(int $id): ?Censo {
        $censo = $this->repository->findById($id);

        if (!$censo) {
            throw new \InvalidArgumentException("Censo con id $id no encontrado");
        }

        return $censo;
    }
}