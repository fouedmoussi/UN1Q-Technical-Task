<?php

namespace Src\Calendar\Event\Presentation\API;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Src\Calendar\Event\Application\Exceptions\EventOverlapsException;
use Src\Calendar\Event\Application\Mappers\EventMapper;
use Src\Calendar\Event\Application\UseCases\Commands\DeleteEventCommand;
use Src\Calendar\Event\Application\UseCases\Commands\StoreEventCommand;
use Src\Calendar\Event\Application\UseCases\Commands\UpdateEventCommand;
use Src\Calendar\Event\Application\UseCases\Queries\FindAllEventsQuery;
use Src\Calendar\Event\Infrastructure\Requests\CreateEventRequest;
use Src\Calendar\Event\Infrastructure\Requests\FilterEventsRequest;
use Src\Calendar\Event\Infrastructure\Requests\UpdateEventRequest;
use Symfony\Component\HttpFoundation\Response;

class EventController
{
    public const DEFAULT_PER_PAGE = 15;

    public function index(FilterEventsRequest $request): JsonResponse
    {
        $filters = [];
        $filters['start'] = $request->input('start');
        $filters['end'] = $request->input('end');

        $perPage = (int) $request->input('per_page', self::DEFAULT_PER_PAGE);

        return response()->json((new FindAllEventsQuery())->handle($filters, $perPage));
    }

    public function store(CreateEventRequest $request): JsonResponse
    {
        try {
            $newEvent = EventMapper::fromRequest($request);
            $events = (new StoreEventCommand($newEvent))->execute();

            return response()->json(['data' => $events], Response::HTTP_CREATED);
        } catch (EventOverlapsException $exception) {
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function update(int $id, UpdateEventRequest $request): JsonResponse
    {
        try {
            $event = EventMapper::fromRequest($request, $id);
            $event = (new UpdateEventCommand($event))->execute();

            return response()->json(['data' => $event], Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => "We couldn't find the event you're trying to update."], Response::HTTP_NOT_FOUND);
        } catch (EventOverlapsException $exception) {
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            (new DeleteEventCommand($id))->execute();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => "We couldn't find the event you're trying to delete."], Response::HTTP_NOT_FOUND);
        }
    }
}
