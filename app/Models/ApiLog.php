<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    protected $fillable = [
        'user_id',
        'ip',
        'method',
        'url',
        'payload',
        'status_code',
        'duration'
    ];
}
