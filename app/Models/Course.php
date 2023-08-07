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
    protected $hidden = ['golongan'];
    protected $appends = ['golongan_name','golongan_code'];

    public function golongan() {
        return $this->belongsTo(Golongan::class);
    }

    public function pre_test_questions()
    {
        return $this->hasMany(TestQuestion::class)->where('is_pre_test','=',1);
    }

    public function post_test_questions()
    {
        return $this->hasMany(TestQuestion::class)->whereNull('is_pre_test');
    }

    public function getGolonganNameAttribute()
    {
        return $this->golongan ? $this->golongan->name : null;
    }
    
    public function getGolonganCodeAttribute()
    {
        return $this->golongan ? $this->golongan->code : null;
    }
}
