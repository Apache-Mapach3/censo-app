<?php

namespace App\Censo\Application\UseCase;

use App\Censo\Domain\Repository\CensoRepository;

class ListarCensosUseCase {

    public function __construct(private CensoRepository $repository) {}

    public function execute(): array {
        return $this->repository->findAll();
    }
}