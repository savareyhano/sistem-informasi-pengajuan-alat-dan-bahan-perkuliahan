<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'username', 'password', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function programStudies()
    {
        return $this->hasMany('App\ProgramStudy');
    }

    public function negotiations()
    {
        return $this->hasMany('App\Negotiation');
    }

    public function submissions()
    {
        return $this->hasManyThrough('App\Submission', 'App\ProgramStudy');
    }

    public function isAdministrator()
    {
        return $this->role == 'administrator';
    }

    public function scopeNotMyself($query)
    {
        return $query->where('id', '<>', Auth::id());
    }

    public function scopeProdi($query)
    {
        return $query->where('role', 'prodi');
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
