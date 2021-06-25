<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function pre_test_questions()
    {
        return $this->hasMany(TestQuestion::class)->where('is_pre_test','=',1);
    }

    public function post_test_questions()
    {
        return $this->hasMany(TestQuestion::class)->whereNull('is_pre_test');
    }
}
