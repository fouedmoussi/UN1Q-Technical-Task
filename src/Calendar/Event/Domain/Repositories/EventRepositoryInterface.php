<?php

namespace Src\Calendar\Event\Domain\Repositories;

use Src\Calendar\Event\Domain\Model\Event;

interface EventRepositoryInterface
{
    public function findAll(array $filters = [], int $perPage): array;

    public function store(array $events): array;

    public function update(Event $event): array;

    public function delete(int $event_id): void;
}
