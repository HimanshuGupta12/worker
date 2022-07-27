<?php

namespace App\Mail;

use App\Models\Hour;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LateHourSubmissionNotificationMail extends Mailable
{
    use Queueable, SerializesModels;


    private $company;
    private $submittedHour;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($company, Hour $submittedHour)
    {
        $this->company = $company;
        $this->submittedHour = $submittedHour;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notification.late_hours',[
            'company' => $this->company,
            'hour' => $this->submittedHour
        ]);
    }
}
