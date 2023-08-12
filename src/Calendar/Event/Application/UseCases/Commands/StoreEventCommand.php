<?php

namespace Src\Calendar\Event\Application\UseCases\Commands;

use Src\Calendar\Event\Domain\Model\Event;
use Src\Calendar\Event\Domain\Repositories\EventRepositoryInterface;
use Src\Common\Domain\CommandInterface;

class StoreEventCommand implements CommandInterface
{
    private EventRepositoryInterface $repository;

    public function __construct(
        private readonly Event $event
    ) {
        $this->repository = app()->make(EventRepositoryInterface::class);
    }

    public function execute(): array
    {
        $eventsToInsert = [];

        if ($this->event->recurringFrequency?->value) {
            $intervalUnit = $this->event->recurringFrequency->intervalUnit();
            $diffs = $this->event->end->{'diffIn'.$intervalUnit}($this->event->recurringEndsAt);
            $eventsToInsert = $this->generateRecurrences($diffs, $intervalUnit);
        }

        array_unshift($eventsToInsert, $this->event);

        return $this->repository->store($eventsToInsert);
    }

    private function generateRecurrences(int $diffs, string $intervalUnit): array
    {
        $recurrencesEvents = [];
        for ($interval = 1; $interval <= $diffs; ++$interval) {
            $recurrencesEvents[] = new Event(
                id: null,
                title: $this->event->title,
                description: $this->event->description,
                start: $this->event->start->{'add'.$intervalUnit}($interval),
                end: $this->event->end->{'add'.$intervalUnit}($interval),
            );
        }

        return $recurrencesEvents;
    }
}
