<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Hour;

class HoursNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    
    protected $oldHour, $newHour;


    public function __construct(Hour $oldHour, Hour $newHour)
    {
        $this->oldHour = $oldHour;
        $this->newHour = $newHour;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = ['old_hour' => $this->oldHour, 'new_hour' => $this->newHour];
        return $this->markdown('emails.notification.hours', $data);
//        return $this->from(config('mail.from.name'))
//                ->markdown('emails.notification.hours', $data);
    }
}
