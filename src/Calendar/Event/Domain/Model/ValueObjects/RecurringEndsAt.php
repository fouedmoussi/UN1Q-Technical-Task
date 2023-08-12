<?php

declare(strict_types=1);

namespace Src\Calendar\Event\Domain\Model\ValueObjects;

use Src\Common\Domain\ValueObject;

final class RecurringEndsAt extends ValueObject
{
    private ?string $recurringEndsAt;

    public function __construct(?string $recurringEndsAt)
    {
        $this->recurringEndsAt = $recurringEndsAt;
    }

    public function __toString(): string
    {
        return $this->recurringEndsAt;
    }

    public function jsonSerialize(): ?string
    {
        return $this->recurringEndsAt;
    }
}
