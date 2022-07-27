<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAccessControl extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    // -------------------------------------------- relationships ------------------------------------------------------
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
