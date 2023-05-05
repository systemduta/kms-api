<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pas_kpi extends Model
{
    use HasFactory;
    protected $fillable=[
        '3p_id','dimensi_id','company_id','division_id','max_nilai','name'
    ];
}
