<?php

namespace App;

//use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

//use Caffeinated\Shinobi\Traits\ShinobiTrait;
use Klaravel\Ntrust\Traits\NtrustUserTrait;

class User extends Authenticatable
{
    //use Notifiable;
    //use ShinobiTrait;
    use NtrustUserTrait; // add this trait to your user model

    /*
     * Role profile to get value from ntrust config file.
     */
    protected static $roleProfile = 'user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role'
];

/**
 * The attributes that should be hidden for arrays.
 *
 * @var array
 */
protected $hidden = [
    'password', 'remember_token',
];
}
