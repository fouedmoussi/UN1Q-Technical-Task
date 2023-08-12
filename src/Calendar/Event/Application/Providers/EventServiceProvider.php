<?php

namespace Src\Calendar\Event\Application\Providers;

use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            \Src\Calendar\Event\Domain\Repositories\EventRepositoryInterface::class,
            \Src\Calendar\Event\Application\Repositories\Eloquent\EventRepository::class
        );
    }
}
