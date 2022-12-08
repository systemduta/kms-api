<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vhs_certi extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'doc1',
        'doc2',
        'doc3',
    ];
}
