<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pas_ind_penilaian extends Model
{
    use HasFactory;
    protected $fillable=[
        '3p_id','kpi_id','company_id','division_id','nilai','grade','desc'
    ];
}
