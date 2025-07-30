<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticable
{
    use HasApiTokens, HasUuids, Notifiable;

    protected $fillable = [
        'name', 'username', 'email', 'phone', 'password',
    ];

    protected $hidden = ['password', 'remember_token'];
}
