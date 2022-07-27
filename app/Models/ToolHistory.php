<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolHistory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function possessor()
    {
        return $this->morphTo();
    }

    public static function log(Tool $tool, string $description)
    {
        self::create([
            'tool_id' => $tool->id,
            'description' => $description,
        ]);
    }
}
