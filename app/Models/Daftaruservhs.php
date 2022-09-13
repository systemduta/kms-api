<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daftaruservhs extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'jadwal_id', 'vhs_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwalvhs::class);
    }

    public function vhs()
    {
        return $this->belongsTo(Vhs::class);
    }
}
