<?php

namespace Src\Calendar\Event\Application\UseCases\Commands;

use Src\Calendar\Event\Domain\Repositories\EventRepositoryInterface;
use Src\Common\Domain\CommandInterface;

class DeleteEventCommand implements CommandInterface
{
    private EventRepositoryInterface $repository;

    public function __construct(
        private readonly int $eventId
    ) {
        $this->repository = app()->make(EventRepositoryInterface::class);
    }

    public function execute(): void
    {
        $this->repository->delete($this->eventId);
    }
}
