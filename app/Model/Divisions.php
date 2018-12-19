<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Divisions extends Model
{
    protected $table = 'Divisions';
    protected $fillable = [
        "division_id",
        "event_id",
        "DivisionName",
        "ageMin",
        "ageMax",
        "sex",
        "cost",
        "created_at",
        "updated_at"
    ];
}
