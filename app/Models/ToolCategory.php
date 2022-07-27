<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;

class ToolCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function scopeOrderDefault($q)
    {
        $q->orderBy('name');
    }

    public static function createCategory(Company $company, string $name): self
    {
        if (self::where('company_id', $company->id)->where('name', $name)->exists()) {
            throw ValidationException::withMessages(['Name already exists']);
        }

        return self::create([
            'name' => $name,
            'company_id' => $company->id,
        ]);
    }
}
