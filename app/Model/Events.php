<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    protected $table = 'Events';
    protected $fillable = [
        "id",
        "name",
        "detail",
        "location",
        "amphur_id",
        "province_id",
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
