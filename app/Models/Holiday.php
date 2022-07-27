<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public static $leaveStatus = [
        0 => null,
        1 => 'Approved',
        2 => 'Not Approved',
        3 => 'Pending',
        4 => 'Delete request',
    ];

    // public static $ReportType = [
    //     'one_day' => 'I feel sick and need to go home now',
    //     'today' => 'I am sick and I can not come to work today',
    //     'from' => 'Report my sick days',
    // ];
    
    public static function addUntillDaysInHolidays ($holidays)
    {
        $data = [];
        foreach ($holidays as $key => $holiday) {
            
            $now = time(); // or your date as well
            $your_date = strtotime($holiday['date_from']);
            $datediff = ceil(($your_date - $now)/86400);
            
            $data[$key] = $holiday + ['until_days' => $datediff];
        }
        return $data;
    }
}
