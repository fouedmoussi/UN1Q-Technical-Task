<?php

namespace Tests\Unit;

use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Src\Calendar\Event\Application\Exceptions\EventOverlapsException;
use Src\Calendar\Event\Application\UseCases\Commands\DeleteEventCommand;
use Src\Calendar\Event\Application\UseCases\Commands\StoreEventCommand;
use Src\Calendar\Event\Application\UseCases\Commands\UpdateEventCommand;
use Src\Calendar\Event\Domain\Model\Event;
use Src\Calendar\Event\Domain\Model\ValueObjects\DateTime as EventDateTime;
use Src\Calendar\Event\Domain\Model\ValueObjects\RecurringFrequency;
use Src\Calendar\Event\Infrastructure\EloquentModels\EventEloquentModel;
use Src\Calendar\Event\Infrastructure\Requests\CreateEventRequest;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private function createSampleEvent($start, $end, $recurringFrequency = null, $recurringEndsAt = null)
    {
        $event = new Event(
            id: null,
            title: $this->faker->sentence,
            description: $this->faker->paragraph,
            start: new EventDateTime($start),
            end: new EventDateTime($end),
            recurringFrequency: $recurringFrequency,
            recurringEndsAt: $recurringEndsAt,
        );

        $command = new StoreEventCommand($event);

        return $command->execute();
    }

    public function testCreateEventWithInvalidDuration()
    {
        $this->withoutExceptionHandling();

        $start = CarbonImmutable::tomorrow()->setTime(12, 0, 0);
        $end = $start->addDay();

        $requestData = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'start' => $start->format('Y-m-d H:i:s'),
            'end' => $end->format('Y-m-d H:i:s'),
        ];

        $this->expectException(ValidationException::class);

        $request = new CreateEventRequest();
        $request->merge($requestData);
        $this->app['validator']->validate($request->all(), $request->rules());
    }

    public function testCreateNonRecurrentEvent()
    {
        $start = CarbonImmutable::tomorrow()->setTime(12, 0, 0);
        $end = $start->addHour();

        $createdEvents = $this->createSampleEvent($start, $end);

        $this->assertCount(1, $createdEvents);

        return $createdEvents[0]->id;
    }

    public function testCreateRecurrentEvent()
    {
        $start = CarbonImmutable::tomorrow()->setTime(10, 0, 0);
        $end = $start->addHour();
        $recurringEndsAt = $end->addDays(7);

        $createdEvents = $this->createSampleEvent($start, $end, RecurringFrequency::from('daily'), new EventDateTime($recurringEndsAt));

        $this->assertCount(8, $createdEvents);

        return $createdEvents;
    }

    public function testCreateEventOverlaps()
    {
        $createdEvents = $this->testCreateRecurrentEvent();

        $start = CarbonImmutable::tomorrow()->addDay()->setTime(8, 0, 0);
        $end = $start->addHours(4);

        $event = new Event(
            id: null,
            title: $this->faker->sentence,
            description: $this->faker->paragraph,
            start: new EventDateTime($start),
            end: new EventDateTime($end),
            recurringFrequency: null,
            recurringEndsAt: null,
        );

        $command = new StoreEventCommand($event);
        $this->expectException(EventOverlapsException::class);

        $command->execute();
    }

    public function testUpdateOnlyNeededAttributeEvent()
    {
        $createdEventId = $this->testCreateNonRecurrentEvent();

        $newTitle = 'UPDATED '.$this->faker->sentence;

        $event = new Event(
            id: $createdEventId,
            title: $newTitle,
        );

        $command = new UpdateEventCommand($event);
        $updatedEvent = $command->execute();

        $this->assertEquals($newTitle, $updatedEvent['title']);
    }

    public function testUpdateEventPeriod()
    {
        $createdEventId = $this->testCreateNonRecurrentEvent();

        $newStart = CarbonImmutable::tomorrow()->addDay(5)->setTime(16, 0, 0);
        $newEnd = $newStart->addHour();

        $event = new Event(
            id: $createdEventId,
            start: new EventDateTime($newStart),
            end: new EventDateTime($newEnd),
        );

        $command = new UpdateEventCommand($event);
        $updatedEvent = $command->execute();

        $this->assertEquals($event->start, new EventDateTime(CarbonImmutable::parse($updatedEvent['start'])));
        $this->assertEquals($event->end, new EventDateTime(CarbonImmutable::parse($updatedEvent['end'])));
    }

    public function testUpdateEventOverlaps()
    {
        $createdEvents = $this->testCreateRecurrentEvent();

        $newStart = CarbonImmutable::tomorrow()->addDay()->setTime(9, 0, 0);
        $newEnd = $newStart->addHours(2);

        $event = new Event(
            id: end($createdEvents)->id,
            start: new EventDateTime($newStart),
            end: new EventDateTime($newEnd),
        );

        $command = new UpdateEventCommand($event);
        $this->expectException(EventOverlapsException::class);
        $command->execute();
    }

    public function testDeleteEvent()
    {
        $createdEvents = $this->testCreateRecurrentEvent();

        $eventIdToDelete = $createdEvents[0]->id;

        $command = new DeleteEventCommand($eventIdToDelete);
        $command->execute();

        $deletedEvent = EventEloquentModel::find($eventIdToDelete);

        $this->assertNull($deletedEvent);
    }
}
