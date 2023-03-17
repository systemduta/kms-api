<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalUserVhs extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','jadwal_id','company_id','is_take','isAllow'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function jadwal()
    {
        return $this->belongsTo(Jadwalvhs::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
