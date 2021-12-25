<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestAnswer extends Model
{
    use HasFactory;
    // protected $table = ['test_answers'];
    protected $guarded = ['id'];

    public function test_question()
    {
        return $this->belongsTo(TestQuestion::class);
    }
}
