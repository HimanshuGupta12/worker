<?php

namespace App\Console\Commands\Subscriptions;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\Subscriptions\TrialEndMail;

class TrialEndNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:trial_ending_notification {is_trial_ended=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email notification to users before 3 days of trial ending and on last day of trial and on time when trial ends. Also, after 7, 14 days after trial ends.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $APP_NAME = config('app.name');
        $is_trial_ended = $this->argument('is_trial_ended');
        $todayDate = Carbon::now()->format("Y-m-d");

        $days_before_trial_end = config('constants.TRIAL_END_NOTIFICATION_BEFORE_DAYS');
        $days_after_trial_end_1 = config('constants.TRIAL_END_NOTIFICATION_AFTER_DAYS_FIRST');
        $days_after_trial_end_2 = config('constants.TRIAL_END_NOTIFICATION_AFTER_DAYS_SECOND');
        $date_before_trial_end = Carbon::now()->addDays($days_before_trial_end)->format("Y-m-d");
        $date_after_trial_end_1 = Carbon::now()->subDays($days_after_trial_end_1)->format("Y-m-d");
        $date_after_trial_end_2 = Carbon::now()->subDays($days_after_trial_end_2)->format("Y-m-d");
        
        $users = User::select('id', 'name', 'email', 'trial_ends_at');
        if(!empty($is_trial_ended)) {
            $users = $users->whereBetween('trial_ends_at', [ Carbon::now()->startOfMinute(), Carbon::now()->endOfMinute() ]);
        } else {
            $users = $users->where(function($q) use ($date_before_trial_end, $todayDate) {
                $q->where('trial_ends_at', '>', Carbon::now()->format("Y-m-d H:i:s"));
                $q->where(function($q) use ($date_before_trial_end, $todayDate) {
                    $q->whereDate('trial_ends_at', $date_before_trial_end);
                    $q->orWhereDate('trial_ends_at', $todayDate);
                });
            })->orWhere(function($q) use ($date_after_trial_end_1, $date_after_trial_end_2) {
                $q->where('trial_ends_at', '<', Carbon::now()->format("Y-m-d H:i:s"));
                $q->where(function($q) use ($date_after_trial_end_1, $date_after_trial_end_2) {
                    $q->whereDate('trial_ends_at', $date_after_trial_end_1);
                    $q->orWhereDate('trial_ends_at', $date_after_trial_end_2);
                });
            });
        }
        $users = $users->whereHas('company', function($q) {
            $q->where('disable_subscription', 0);
        })->whereDoesntHave('subscriptions', function($q) {
            $q->where('stripe_status', 'active');
        })
        ->with([ 'user_billing_detail:id,email,user_id' ])
        ->get();
        
        if(!empty($users)) {
            foreach($users as $u => $user) {
                $reminder_type = '';
                $email = (!empty($user->user_billing_detail->email)) ? $user->user_billing_detail->email : $user->email;
                $days = round( (strtotime($user->trial_ends_at) - time()) / 86400 );
                $userDT = datetimeConversionTZ($user->trial_ends_at, 'user');
                $dayName = date('l', strtotime($userDT));
                $dayTime = date('H:i', strtotime($userDT));
                if(!empty($is_trial_ended)) {
                    $reminder_type = 'now';
                    $email_subject = "Your access to {$APP_NAME} account has been limited";
                } else {
                    if($days < 0) {
                        $reminder_type = 'after';
                        $days = abs($days);
                        $email_subject = "Your {$APP_NAME} trial ended {$days} days ago";
                    } else {
                        $reminder_type = 'before';
                        $email_subject = (empty($days)) ? "Your {$APP_NAME} trial is about to end today at {$dayName} {$dayTime}" : "Your {$APP_NAME} trial is about to end in {$days} days at {$dayName} {$dayTime}";
                    }
                }
                try {
                    Mail::to($email)->send(new TrialEndMail($user, $days, $email_subject, $reminder_type));
                    $this->comment('Mail sent to ' . $email);
                } catch (\Exception $e) {
                    $this->error('Error: ' . $e->getMessage());
                }

                unset($reminder_type);
                unset($userDT);
                unset($dayName);
                unset($dayTime);
                unset($email_subject);
                unset($days);
                unset($email);
                unset($user);
            }
        }

        unset($todayDate);
        unset($days_before_trial_end);
        unset($days_after_trial_end_1);
        unset($days_after_trial_end_2);
        unset($date_before_trial_end);
        unset($date_after_trial_end_1);
        unset($date_after_trial_end_2);
        unset($users);
        unset($is_trial_ended);
        unset($APP_NAME);
    }
}
