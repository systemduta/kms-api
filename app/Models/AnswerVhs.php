<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerVhs extends Model
{
    use HasFactory;
    protected $fillable = [
        'materi_id','question_id','user_id','answer'
    ];

    public function materivhs()
    {
        return $this->belongsTo(MateriVhs::class);
    }
    public function questionvhs()
    {
        return $this->belongsTo(QuestionVhs::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
