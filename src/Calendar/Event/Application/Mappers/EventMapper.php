<?php

namespace Src\Calendar\Event\Application\Mappers;

use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Src\Calendar\Event\Domain\Model\Event;
use Src\Calendar\Event\Domain\Model\ValueObjects\DateTime;
use Src\Calendar\Event\Domain\Model\ValueObjects\RecurringFrequency;
use Src\Calendar\Event\Infrastructure\EloquentModels\EventEloquentModel;

class EventMapper
{
    public static function fromRequest(Request $request, ?int $event_id = null): Event
    {
        return new Event(
            id: $event_id,
            title: $request->input('title'),
            description: $request->input('description'),
            start: $request->input('start') ? new DateTime(CarbonImmutable::parse($request->input('start'))) : null,
            end: $request->input('end') ? new DateTime(CarbonImmutable::parse($request->input('end'))) : null,
            recurringFrequency: is_null($event_id) && $request->input('recurring_frequency') ? RecurringFrequency::from($request->input('recurring_frequency')) : null,
            recurringEndsAt: is_null($event_id) && $request->input('recurring_frequency') && $request->input('recurring_ends_at') ? new DateTime(CarbonImmutable::parse($request->input('recurring_ends_at'))) : null,
        );
    }

    public static function fromEloquent(EventEloquentModel $eventEloquent): Event
    {
        return new Event(
            id: $eventEloquent->id,
            title: $eventEloquent->title,
            description: $eventEloquent->description,
            start: new DateTime(CarbonImmutable::parse($eventEloquent->start)),
            end: new DateTime(CarbonImmutable::parse($eventEloquent->end)),
            recurringFrequency: $eventEloquent->recurring_frequency ? RecurringFrequency::from($eventEloquent->recurring_frequency) : null,
            recurringEndsAt: $eventEloquent->recurring_frequency && $eventEloquent->recurring_ends_at ? new DateTime(CarbonImmutable::parse($eventEloquent->recurring_ends_at)) : null,
        );
    }

    public static function toEloquent(Event $event): EventEloquentModel
    {
        $eventEloquent = new EventEloquentModel();
        if ($event->id) {
            $eventEloquent = EventEloquentModel::query()->findOrFail($event->id);
        }
        $eventEloquent->title = $event->title;
        $eventEloquent->description = $event->description;
        $eventEloquent->start = $event->start;
        $eventEloquent->end = $event->end;
        $eventEloquent->recurring_frequency = $event->recurringFrequency?->value;
        $eventEloquent->recurring_ends_at = $event->recurringEndsAt;

        return $eventEloquent;
    }
}
