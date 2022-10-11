<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class MateriVhs extends Model
{
    use HasFactory, SoftDeletes, Notifiable;
    protected $fillable = [
        'name','desc','type','jadwal_id','image','file','video'
    ];

    public function jadwalvhs()
    {
        return $this->belongsTo(jadwalvhs::class);
    }
}