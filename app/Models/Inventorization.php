<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventorization extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function enableInventorization(Company $company, int $month_day, string $sms_message)
    {
        if ($month_day <= 0 || $month_day > 28) {
            throw new \Exception('out of range numbers');
        }

        $row = self::where('company_id', $company->id)->first();
        if (!$row) {
            $row = new self;
            $row->company_id = $company->id;
        }

        $row->month_day = $month_day;
        $row->sms_message = $sms_message;
        $row->save();


        $date = now()->startOfMonth()->addDays($month_day - 1);
        if ($date->copy()->endOfDay()->isPast()) {
            $date = now()->startOfMonth()->addMonthsNoOverflow()->addDays($month_day - 1);
        }
        $company->tools()->wherePossessorIsWorker()->update([
            'next_inventorization_at' => $date,
        ]);
    }

    public static function disableInventorization(Company $company)
    {
        self::where('company_id', $company->id)->delete();
        Tool::disableInventorization($company);

    }

    public function nextInventorizationDate(): Carbon
    {
        return now()->startOfMonth()->addMonthNoOverflow()->addDays($this->month_day - 1);
    }
}
