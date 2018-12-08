<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Divisions extends Model
{
    protected $table = 'Divisions';
    protected $fillable = [
        "id",
        "events_id",
        "name",
        "ageMin",
        "ageMax",
        "sex",
        "cost",
        "created_at",
        "updated_at"
    ];
}
