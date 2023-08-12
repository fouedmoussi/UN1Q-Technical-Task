<?php

namespace Src\Calendar\Event\Infrastructure\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class SameDay implements Rule
{
    private $startDate;

    public function __construct($startDate)
    {
        $this->startDate = $startDate;
    }

    public function passes($attribute, $value)
    {
        if (!Carbon::hasFormat($this->startDate, 'Y-m-d H:i:s') || !Carbon::hasFormat($value, 'Y-m-d H:i:s')) {
            return false;
        }

        $startDateTime = Carbon::parse($this->startDate);
        $endDateTime = Carbon::parse($value);

        return $endDateTime->isSameDay($startDateTime);
    }

    public function message()
    {
        return 'The :attribute must be on the same day as the start date';
    }
}
