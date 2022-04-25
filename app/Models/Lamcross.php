<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lamcross extends Model
{
    use HasFactory;

    protected $fillable = [
        'crossfunction_id','file','company_id','organization_id','status','name'
    ];

    protected $guarded = [
        'id'
    ];

    public function crossfunction()
    {
        return $this->belongsTo(Crossfunction::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
