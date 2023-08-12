<?php

namespace Src\Calendar\Event\Application\Exceptions;

final class EventOverlapsException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Sorry! It looks like the selected event time overlaps with an existing event.');
    }
}
