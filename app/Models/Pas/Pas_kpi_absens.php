<?php

namespace App\Models\Pas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pas_kpi_absens extends Model
{
    use HasFactory;
    protected $fillable = [
        'penilaianAbsen_id','kpi_id','nilai'
    ];
}
