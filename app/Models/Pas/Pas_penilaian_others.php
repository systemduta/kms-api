<?php

namespace App\Models\Pas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pas_penilaian_others extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','dimensi_id','kpi_id','date','nilai','max_nilai'
    ];
}
