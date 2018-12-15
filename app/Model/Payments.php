<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    protected $table = 'Payment';
    protected $fillable = [
        "id",
        "user_id",
        "BankName",
        "AccountName",
        "AccountNum",
        "created_at",
        "updated_at"
    ];
}
