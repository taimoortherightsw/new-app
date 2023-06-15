<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_name',
        'password',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'user_name', 'user_name');
    }
}
