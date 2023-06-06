<?php

namespace App\Models\Pas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pas_final_skors extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'date','user_id','nilai'
    ];
}
