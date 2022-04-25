<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function username()
    {
        return 'username';
    }
    protected $guarded = [
        'id'
    ];

    protected $appends = ['ui_avatar'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function golongan()
    {
        return $this->belongsTo(Golongan::class);
    }

    public function getUiAvatarAttribute()
    {
        return 'https://ui-avatars.com/api/?background=0D8ABC&color=fff&name='.$this->name;
    }
}
