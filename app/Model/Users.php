<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'Users';
    protected $fillable = [
        "id",
        "line_id",
        "line_pic",
        "line_token",
        "type",
        "firstname",
        "lastname",
        "sex",
        "address",
        "PROVINCE_ID",
        "AMPHUR_ID",
        "birthday",
        "picID",
        "picORG",
        "OrgName",
        "tradeNum",
        "tel",
        "status",
        "remember_token",
        "created_at",
        "updated_at"
    ];
}
