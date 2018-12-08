<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Pics extends Model
{
    protected $table = 'Pics';
    protected $fillable = [
        "id",
        "event_id",
        "name",
        "created_at",
        "updated_at"
    ];
}
