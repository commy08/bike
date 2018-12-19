<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    protected $table = 'Events';
    protected $fillable = [
        "event_id",
        "EventName",
        "detail",
        "location",
        "amphurs",
        "provinces",
        "dateClose",
        "dateDeadline",
        "dateRace",
        "type",
        "rule",
        "user_id",
        "reward",
        "payment",
        "status",
        "created_at",
        "updated_at"
    ];
}
