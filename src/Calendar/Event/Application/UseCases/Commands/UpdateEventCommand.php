<?php

namespace Src\Calendar\Event\Application\UseCases\Commands;

use Src\Calendar\Event\Domain\Model\Event;
use Src\Calendar\Event\Domain\Repositories\EventRepositoryInterface;
use Src\Common\Domain\CommandInterface;

class UpdateEventCommand implements CommandInterface
{
    private EventRepositoryInterface $repository;

    public function __construct(
        private readonly Event $event
    ) {
        $this->repository = app()->make(EventRepositoryInterface::class);
    }

    public function execute(): array
    {
        return $this->repository->update($this->event);
    }
}
