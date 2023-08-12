<?php

declare(strict_types=1);

namespace Src\Calendar\Event\Domain\Model\ValueObjects;

use Carbon\CarbonImmutable;
use Src\Common\Domain\ValueObject;

final class DateTime extends ValueObject
{
    private CarbonImmutable $value;

    public function __construct(CarbonImmutable $value)
    {
        $this->value = $value;
    }

    public function addDays(int $days): self
    {
        return new self($this->value->addDays($days));
    }

    public function addWeeks(int $weeks): self
    {
        return new self($this->value->addWeeks($weeks));
    }

    public function addMonths(int $months): self
    {
        return new self($this->value->addMonths($months));
    }

    public function addYears(int $years): self
    {
        return new self($this->value->addYears($years));
    }

    public function diffInDays(DateTime $date): int
    {
        return $this->value->diffInDays($date->getValue());
    }

    public function diffInWeeks(DateTime $date): int
    {
        return $this->value->diffInWeeks($date->getValue());
    }

    public function diffInMonths(DateTime $date): int
    {
        return $this->value->diffInMonths($date->getValue());
    }

    public function diffInYears(DateTime $date): int
    {
        return $this->value->diffInYears($date->getValue());
    }

    public function getValue(): CarbonImmutable
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value->format('Y-m-d H:i:s');
    }

    public function jsonSerialize(): string
    {
        return $this->value->format('Y-m-d\\TH:i:sO');
    }
}
