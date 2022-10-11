<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomsVhs extends Model
{
    use HasFactory;
    protected $fillable = [
        'jadwal_id','name','times','link','meeting_id','password'
    ];

    public function jadwalvhs()
    {
        return $this->belongsTo(jadwalvhs::class);
    }
}
