<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_email_id',
        'user_address',
        'user_contact',
    ];

    public function login()
    {
        return $this->belongsTo(Login::class, 'user_name', 'user_name');
    }
}
