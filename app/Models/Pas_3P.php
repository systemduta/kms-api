<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pas_3P extends Model
{
    use HasFactory;
    protected $table = 'pas_3p';
    protected $fillable=[
        'name','persentase'
    ];
}
