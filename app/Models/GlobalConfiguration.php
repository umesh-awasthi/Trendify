<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'email',
        'password',
        'api_url',
        'selection',
    ];

}
