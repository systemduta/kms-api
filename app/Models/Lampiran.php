<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lampiran extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'name', 'file', 'company_id', 'organization_id','image'
        'sop_id','file','company_id','organization_id','status','name'
    ];

    protected $guarded = [
        'id'
    ];

    public function sop()
    {
        return $this->belongsTo(Sop::class);
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
