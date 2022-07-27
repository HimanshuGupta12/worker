<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qr extends Model
{
    use HasFactory;

    protected $table = 'legacy_qr_codes';
    protected $guarded = [];

    public static function url($tool_id, string $label): string
    {
        if(gettype($tool_id) == 'string') {
            $encoded = $tool_id;
        } else {
            $encoded = self::encodeId($tool_id);
        }
        return route('qr', [$encoded, 'label' => $label]);
    }

    public static function decodeToolId(string $legacy_or_new_id): int|null
    {
        $legacy = self::where('legacy_qr', $legacy_or_new_id)->first();
        if ($legacy) {
            return $legacy->tool_id; // was legacy tool id
        }

        $encoded_qr = $legacy_or_new_id;

        $hashids = new \Hashids\Hashids('yXoXZlnXO3Rohvi6Xi9l', 10);
        $decoded = $hashids->decode($encoded_qr);
        if (!empty($decoded)) {
            return $decoded[0]; // was encoded tool id
        }

//        if (!app()->environment('production')) {
//            if (is_numeric($legacy_or_new_id)) {
//                return $legacy_or_new_id; // was not encoded tool id
//            }
//        }

        return null;
    }

    public static function encodeId(int $tool_id): string
    {
        $hashids = new \Hashids\Hashids('yXoXZlnXO3Rohvi6Xi9l', 10);
        return $hashids->encode($tool_id);
    }
}
