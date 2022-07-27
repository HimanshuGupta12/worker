<?php

function user() {
    return \Illuminate\Support\Facades\Auth::user();
}

function worker(): \App\Models\Worker {
//    return cache()->driver('array')->remember('logged_in_worker', null, function () {
//        return \App\Models\Worker::loggedIn();
//    });

    return cache()->driver('array')->rememberForever('logged_in_worker', function () {
        return \App\Models\Worker::loggedIn();
    });
}

function sms(string $number, string $text) {
    // if (!app()->isProduction()) {
    //     \Illuminate\Support\Facades\Log::info("$number: $text" . microtime(true));
    //     return;
    // }

    $sid = config('services.twilio.sid');
    $token = config('services.twilio.token');
    $client = new Twilio\Rest\Client($sid, $token);

    $client->messages->create($number, [
        'from' => 'WorkerNotif',
        'body' => $text,
    ]);
}

//function only for welcome
function welcomesms(string $number, string $text) {
    if (!app()->isProduction()) {
        \Illuminate\Support\Facades\Log::info("$number: $text" . microtime(true));
        return;
    }

    $sid = config('services.twilio.sid');
    $token = config('services.twilio.token');
    $client = new Twilio\Rest\Client($sid, $token);

    $client->messages->create($number, [
        'from' => 'Worker',
        'body' => $text,
    ]);
}

function button($method, $url, $title, $html, $question = false) {
    $csrf = csrf_field();
    $e_title = e($title);
    $e_url = e($url);
    $question_html = $question ? 'onclick="return confirm(\'Are you sure?\');"' : '';
    $method_html = $method !== strtolower('post') ? method_field($method) : '';

    return <<<OUTPUT
        <form method="post" action="{$e_url}" style="display: inline-block;">
            {$csrf}
            {$method_html}
            <button $html $question_html type="submit">{$e_title}</button>
        </form>
OUTPUT;
}

function fileExtension($filename) {
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $extension = strtolower($extension);

    if (!$extension) {
        throw new \Exception('no extension');
    }
    if (!in_array(strlen($extension), [3, 4])) {
        throw new \Exception('strange extension: ' . $extension);
    }

    return $extension;
}

function email() {
    return new \App\Services\Email();
}

function dateFormat(): string {
    return 'd.m.Y';
}

function dateTimeFormat(): string {
    return 'd.m.Y H:i:s';
}

// when calling this function it waits while it gets lock
// if it can't get lock for 60 seconds, it will throw exception
function getLock($name, $timeout = 60) {
    $result = \Illuminate\Support\Facades\DB::selectOne(
        \Illuminate\Support\Facades\DB::raw("SELECT GET_LOCK(:name, :timeout);"),
        compact('name', 'timeout')
    );

    $array = (array)$result;
    $first = array_pop($array);

    if ($first !== 1) {
        throw new \Exception("Couldn't get lock: $name");
    }
}

// release lock
// locks are automatically released when php disconnects from mysql
function releaseLock($name) {
    \Illuminate\Support\Facades\DB::statement(
        \Illuminate\Support\Facades\DB::raw("SELECT RELEASE_LOCK(:name);"),
        compact('name')
    );
}

function js_translations ($lang) {
    $data = [
        "From" => __("From"),
        "to" => __("to"),
        "Submitted hours on this day" => __("Submitted hours on this day"),
        "Submit" => __("Submit"),
        "Next" => __("Next"),
        "Previous" => __("Previous"),
        "Cancel" => __("Cancel"),
        "current step:" => __("current step:"),
        "Pagination" => __("Pagination"),
        "Loading ..." => __("Loading ..."),
        "Please select start and finish time wisely. The work duration must have some positive value after deduction of break time." => __("Please select start and finish time wisely. The work duration must have some positive value after deduction of break time."),
        "Hours already submitted on this time." => __("Hours already submitted on this time."),
        "Select both start and end time to proceed next." => __("Select both start and end time to proceed next."),
        "Attach photo to proceed further." => __("Attach photo to proceed further."),
        "Fix image validation errors to proceed next" => __("Fix image validation errors to proceed next"),
        "Are you sure to delete these hours..?" => __("Are you sure to delete these hours..?"),
        "Scan tool code" => __("Scan tool code"),
        "Hey! I need access to your camera" =>__("Hey! I need access to your camera"),
        "Do you even have a camera on your device?" =>__("Do you even have a camera on your device?"),
        "Seems like this page is served in non-secure context (HTTPS, localhost or file://)" =>__("Seems like this page is served in non-secure context (HTTPS, localhost or file://)"),
        "Could not access your camera. Is it already in use?" =>__("Could not access your camera. Is it already in use?"),
        "Constraints do not match any installed camera. Did you asked for the front camera although there is none?" =>__("Constraints do not match any installed camera. Did you asked for the front camera although there is none?"),
        "UNKNOWN ERROR" =>__("UNKNOWN ERROR"),
        "Start time" => __("Start time"),
        "End time" => __("End time"),
        "Confirm" => __("Confirm"),
    ];

    return GuzzleHttp\json_encode($data);
}

/*
 * Return cookie by name
 */
function getCustomCookie($name) {
    $data = \Cookie::get($name);
    $data = (!empty($data)) ? json_decode($data, true) : [];
    return $data;
}

/*
 * Return currency by country code
 */
/*function getCurrency($country) {
    $data = [
        'DK' => 'DKK',
        'DE' => 'EUR',
        'LV' => 'EUR',
        'LT' => 'EUR',
        'NO' => 'NOK',
        'PL' => 'PLN',
        'SE' => 'SEK',
        'GB' => 'GBP',
    ];
    return (!empty($data[$country])) ? $data[$country] : '';
}*/

/*
 * Return stripe amount
 */
function formatStripeAmount($amount) {
    return '€' . ($amount / 100);
}

function isMobile() {
    if(!empty($_SERVER['HTTP_USER_AGENT'])){
       $user_ag = $_SERVER['HTTP_USER_AGENT'];
       if(preg_match('/(Mobile|Android|Tablet|GoBrowser|[0-9]x[0-9]*|uZardWeb\/|Mini|Doris\/|Skyfire\/|iPhone|Fennec\/|Maemo|Iris\/|CLDC\-|Mobi\/)/uis',$user_ag)){
          return true;
       };
    };
    return false;
}

function getDateRangeFromDateOption()
{
    $start_date = $end_date = null;
    if (request('date')=='Last week') {
        $previous_week = strtotime("-1 week +1 day");
        $start_week = strtotime("last sunday midnight",$previous_week);
        $end_week = strtotime("next saturday",$start_week);
        $start_date = date("Y-m-d",$start_week);
        $end_date = date("Y-m-d",$end_week);
    } elseif (request('date')=='Previous two weeks') {
        $previous_two_week = strtotime("-2 week +1 day");
        $start_two_week = strtotime("last sunday midnight",$previous_two_week);//1

        $previous_week = strtotime("-1 week +1 day");
        $start_week = strtotime("last sunday midnight",$previous_week);
        $end_week = strtotime("next saturday",$start_week);//2

        $start_date = date("Y-m-d",$start_two_week);
        $end_date = date("Y-m-d",$end_week);
    } elseif (request('date')=='This week') {
        $start_week = strtotime("last sunday midnight");
        $start_date = date("Y-m-d",$start_week);
        $end_date = date("Y-m-d",strtotime(date('y-m-d')));
    } elseif (request('date')=='Last and this week') {
        $previous_week = strtotime("-1 weeks +1 day");
        $start_week = strtotime("last sunday midnight",$previous_week);
        $start_date = date("Y-m-d",$start_week);
        $end_date = date("Y-m-d",strtotime(date('y-m-d')));
    } elseif (request('date')=='Last month') {
        $start_date = date("Y-n-j", strtotime("first day of previous month"));
        $end_date = date("Y-n-j", strtotime("last day of previous month"));
    } elseif (request('date')=='This month') {
        $start_date = date("Y-n-j", strtotime("first day of this month"));
        $end_date = date("Y-n-j", strtotime(date('y-m-d')));
    } elseif (request('date')=='Last three months') {
        $start_date = date("Y-n-j", strtotime("-3 Months"));
        $end_date = date("Y-n-j", strtotime(date('y-m-d')));
    } elseif (request('date')=='Last six months') {
        $start_date = date("Y-n-j", strtotime("-6 Months"));
        $end_date = date("Y-n-j", strtotime(date('y-m-d')));
    } elseif (request('date')=='This year') {
        $start_date = date("Y-n-j", strtotime("first day of january this year"));
        $end_date = date("Y-n-j", strtotime(date('y-m-d')));
    } elseif (request('date')=='Last year') {
        $start_date = date("Y-n-j", strtotime("first day of january last year"));
        $end_date = date("Y-n-j", strtotime("last day of december last year"));
    } elseif (request('date')=='Last twelve months') {
        $start_date = date("Y-n-j", strtotime("-12 Months"));
        $end_date = date("Y-n-j", strtotime(date('y-m-d')));
    } elseif (request('date') == 'Custom' && !empty(request('start_date')) && !empty(request('end_date'))) {
        $start_date = request('start_date');
        $end_date = request('end_date');
    }
    return $data = [ 'start_date' => $start_date , 'end_date' => $end_date ];
}

function salary_format($salary){
    return number_format($salary, 2, ',', '.');
}

/*
 * Datetime timezone conversions
 */
function datetimeConversionTZ($datetime, $conversion_type, $format = 'Y-m-d H:i:s') {
    try {
        if($conversion_type == 'server') {// User TZ to Server TZ
            $tz_from = config('constants.DEFAULT_USER_TIMEZONE');
            $tz_to = config('constants.DEFAULT_SERVER_TIMEZONE');
        } else if($conversion_type == 'user') {// Server TZ to User TZ
            $tz_from = config('constants.DEFAULT_SERVER_TIMEZONE');
            $tz_to = config('constants.DEFAULT_USER_TIMEZONE');
        }
        $datetime = date('Y-m-d H:i:s', strtotime($datetime));
        $dt = new DateTime($datetime, new DateTimeZone($tz_from));
        $dt->setTimeZone(new DateTimeZone($tz_to));
        return $dt->format($format);
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::info("Error (datetimeConversionTZ): " . $e->getMessage());
        return $datetime;
    }
}
