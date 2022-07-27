<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    // ------------------------------------------ relationships --------------------------------------------------------
    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    // ------------------------------------------- scopes --------------------------------------------------------------

    public function scopeFilter($q)
    {
        if (request('client_id')) {
            $q->where('id', request('client_id'));
        }
    }

    public function scopeOrderDefault($q)
    {
        $q->orderBy('name');
    }
    
    // ------------------------------------------- methods --------------------------------------------------------------
    
    
}
