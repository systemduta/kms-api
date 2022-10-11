<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserScoreVhs extends Model
{
    use HasFactory;
    protected $fillable = [
        'materi_id','user_id','score'
    ];

    public function materivhs()
    {
        return $this->belongsTo(MateriVhs::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
