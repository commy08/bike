<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    protected $table = 'Invoices';
    protected $fillable = [
        "invioce_id",
        "user_id",
        "division_id",
        "status",
        "pic",
        "created_at",
        "updated_at"
    ];
}
