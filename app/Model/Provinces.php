<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Provinces extends Model
{
    protected $table = 'Provinces';
    protected $fillable = [
        "id",
        "province_code",
        "province_name",
        "province_name_eng",
        "geo_id",
        "created_at",
        "updated_at"
    ];
}
