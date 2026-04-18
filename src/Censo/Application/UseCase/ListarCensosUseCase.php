<?php

namespace App\Censo\Application\UseCase;

use App\Censo\Domain\Repository\CensoRepository;

class ListarCensosUseCase {

    public function __construct(private CensoRepository $repository) {}

    public function execute(?int $orgId = null): array {
        return $this->repository->findAll($orgId);
    }
}