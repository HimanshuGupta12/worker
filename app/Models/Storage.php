<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;

class Storage extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function tools()
    {
        return $this->morphMany(Tool::class, 'possessor');
    }

    public function toolsNeedInventorization()
    {
        return $this->tools()->needInventorization();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // -------------------------------------------- scopes -------------------------------------------------------------

    public function scopeOrderDefault($q)
    {
        $q->orderBy('name');
    }

    // --------------------------------------------- methods -----------------------------------------------------------

    public function findTool(string $qr_code): Tool
    {
        $company_tool = $this->company->findTool($qr_code);
        if (!$company_tool->possessor || !$company_tool->possessor->is($this)) {
            throw ValidationException::withMessages(['This tool is not assigned to this storage']);
        }

        return $this->tools()->find($company_tool->id);
    }

    public static function createStorage(Company $company, string $name, string $address = null): self
    {
        return self::create([
            'company_id' => $company->id,
            'name' => $name,
            'address' => $address,
        ]);
    }

    public function possessorName(): string
    {
        return 'Storage: ' . $this->name;
    }

    public function requestInventorization()
    {
        Tool::inventoryStorage($this);
    }

    public static function needsInventory(Company $company): bool
    {
        return Storage::where('company_id', $company->id)->whereHas('tools', function ($q) {
            $q->needInventorization();
        })->exists();
    }
}
