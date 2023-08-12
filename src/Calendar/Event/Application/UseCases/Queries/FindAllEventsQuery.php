<?php

namespace Src\Calendar\Event\Application\UseCases\Queries;

use Src\Calendar\Event\Domain\Repositories\EventRepositoryInterface;
use Src\Common\Domain\QueryInterface;

class FindAllEventsQuery implements QueryInterface
{
    private EventRepositoryInterface $repository;

    public function __construct()
    {
        $this->repository = app()->make(EventRepositoryInterface::class);
    }

    public function handle(array $filters = [], ?int $perPage = null): array
    {
        return $this->repository->findAll($filters, $perPage);
    }
}
