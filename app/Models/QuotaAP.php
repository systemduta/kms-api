<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotaAP extends Model
{
    use HasFactory;
    protected $table = 'quotaaps';
    protected $fillable=[
        'jadwal_id','company_id','quota'
    ];
}
