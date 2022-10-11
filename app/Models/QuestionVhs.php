<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionVhs extends Model
{
    use HasFactory;
    protected $fillable = [
        'materi_id','question',
    ];

    public function materivhs()
    {
        return $this->belongsTo(MateriVhs::class);
    }
}
