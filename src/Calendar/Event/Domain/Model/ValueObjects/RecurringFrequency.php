<?php

namespace Src\Calendar\Event\Domain\Model\ValueObjects;

enum RecurringFrequency: string
{
    case DAILY = 'daily';
    case WEEKLY = 'weekly';
    case MONTHLY = 'monthly';
    case YEARLY = 'yearly';

    public function intervalUnit(): string
    {
        return match ($this) {
            RecurringFrequency::DAILY => 'Days',
            RecurringFrequency::WEEKLY => 'Weeks',
            RecurringFrequency::MONTHLY => 'Months',
            RecurringFrequency::YEARLY => 'Years',
        };
    }
}
