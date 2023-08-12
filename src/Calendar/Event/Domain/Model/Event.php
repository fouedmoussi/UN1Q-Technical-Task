<?php

namespace Src\Calendar\Event\Domain\Model;

use Src\Calendar\Event\Domain\Model\ValueObjects\DateTime;
use Src\Calendar\Event\Domain\Model\ValueObjects\RecurringFrequency;
use Src\Common\Domain\AggregateRoot;

class Event extends AggregateRoot implements \JsonSerializable
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly ?string $title = null,
        public readonly ?string $description = null,
        public readonly ?DateTime $start = null,
        public readonly ?DateTime $end = null,
        public readonly ?RecurringFrequency $recurringFrequency = null,
        public readonly ?DateTime $recurringEndsAt = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'start' => $this->start,
            'end' => $this->end,
            'recurring_frequency' => $this->recurringFrequency?->value,
            'recurrring_ends_at' => $this->recurringEndsAt,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
