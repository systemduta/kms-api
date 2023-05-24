<?php

namespace App\Models\Pas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pas_penilaian_absens extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','dimensi_id','date','nilai','max_nilai'
    ];
}
