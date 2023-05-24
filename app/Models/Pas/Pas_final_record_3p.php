<?php

namespace App\Models\Pas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pas_final_record_3p extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','id_3p','date','nilai'
    ];
}
