<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sop extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'organization_id', 'title', 'description', 'file','status'
    ];

    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function lampiran()
    {
        return $this->hasMany(Lampiran::class);
    }
}
