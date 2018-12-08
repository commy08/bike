<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Amphurs extends Model
{
    protected $table = 'Amphurs';
    protected $fillable = [
        "id",
        "amphur_code",
        "amphur_name",
        "amphur_name_eng",
        "geo_id",
        "province_id",
        "created_at",
        "updated_at"
    ];
}
