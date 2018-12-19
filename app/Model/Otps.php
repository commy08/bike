<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Otps extends Model
{
    protected $table = 'Otps';
    protected $fillable = [
        "id",
        "ref",
        "num"
    ];
}
