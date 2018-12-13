<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Banks extends Model
{
    protected $table = 'Bank';
    protected $fillable = [
        "id",
        "BankName",
        "BankCode",
        "created_at",
        "updated_at"
    ];
}
