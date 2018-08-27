<?php

namespace App;

use Illuminate\Notifications\Notifiable;

class User extends \Illuminate\Foundation\Auth\User {
    use Notifiable;

    /** @var string[] */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /** @var string[] */
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
