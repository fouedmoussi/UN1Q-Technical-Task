<?php

namespace Src\Calendar\Event\Application\Repositories\Eloquent;

use Src\Calendar\Event\Application\Exceptions\EventOverlapsException;
use Src\Calendar\Event\Application\Mappers\EventMapper;
use Src\Calendar\Event\Domain\Model\Event;
use Src\Calendar\Event\Domain\Repositories\EventRepositoryInterface;
use Src\Calendar\Event\Infrastructure\EloquentModels\EventEloquentModel;

class EventRepository implements EventRepositoryInterface
{
    public function findAll(array $filters = [], int $perPage): array
    {
        $query = EventEloquentModel::query();

        if (!empty($filters['start']) && !empty($filters['end'])) {
            $query->whereBetween('start', [$filters['start'], $filters['end']])
            ->orWhereBetween('end', [$filters['start'], $filters['end']]);
        }

        return $query->paginate($perPage)->toArray();
    }

    public function eventOverlaps(Event $event): bool
    {
        if (is_null($event->start) && is_null($event->end)) {
            return false;
        }

        $query = EventEloquentModel::query();

        if ($event->id) {
            $query->where('id', '!=', $event->id); // Exclude the current event (for updates)
        }

        return $query->where(function ($q) use ($event) {
            $q->whereBetween('start', [$event->start, $event->end])
                ->orWhereBetween('end', [$event->start, $event->end])
                ->orWhere(function ($q) use ($event) {
                    $q->where('start', '<', $event->start)
                        ->where('end', '>', $event->end);
                });
        })
        ->exists();
    }

    public function eventsOverlap(array $events): bool
    {
        $overlaps = false;

        foreach ($events as $event) {
            if ($this->eventOverlaps($event)) {
                $overlaps = true;
                break;
            }
        }

        return $overlaps;
    }

    public function store(array $eventsToInsert): array
    {
        if ($this->eventsOverlap($eventsToInsert)) {
            throw new EventOverlapsException();
        }

        $insertedEvents = [];
        foreach ($eventsToInsert as $event) {
            $eventEloquent = EventMapper::toEloquent($event);
            $eventEloquent->save();
            $insertedEvents[] = $eventEloquent;
        }

        return $insertedEvents;
    }

    public function update(Event $event): array
    {
        $eventEloquent = EventEloquentModel::query()->findOrFail($event->id);

        if ($this->eventOverlaps($event)) {
            throw new EventOverlapsException();
        }

        $eventArray = $event->toArray();
        $eventEloquent->fill(array_filter($eventArray));
        $eventEloquent->save();

        return $eventEloquent->toArray();
    }

    public function delete(int $event_id): void
    {
        $eventEloquent = EventEloquentModel::query()->findOrFail($event_id);
        $eventEloquent->delete();
    }
}
