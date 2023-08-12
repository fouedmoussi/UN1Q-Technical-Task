<?php

namespace Src\Calendar\Event\Infrastructure\EloquentModels;

use Illuminate\Database\Eloquent\Model;

class EventEloquentModel extends Model
{
    protected $table = 'events';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'start',
        'end',
        'recurring_frequency',
        'recurring_ends_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start' => 'datetime:Y-m-d\\TH:i:sO',
        'end' => 'datetime:Y-m-d\\TH:i:sO',
        'recurring_ends_at' => 'datetime:Y-m-d',
    ];
}
