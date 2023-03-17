<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $table = 'organizations';
    protected $fillable=[
        'company_id','parent_id','code','name','iterasi','is_str','isAdm'
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }
    // public function company()
    // {
    //     return $this->hasMany(Company::class);
    // }
}
