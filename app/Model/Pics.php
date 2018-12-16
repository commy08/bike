<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Pics extends Model
{
    protected $table = 'pics';
    protected $fillable = [
        "id",
        "event_id",
        "PicData",
        "created_at",
        "updated_at"
    ];
}
