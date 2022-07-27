<?php

namespace App\Mail\Subscriptions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrialEndMail extends Mailable
{
    use Queueable, SerializesModels;
    
    protected $user;
    protected $days;
    protected $email_subject;
    protected $reminder_type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $days, $email_subject, $reminder_type)
    {
        $this->user = $user;
        $this->days = $days;
        $this->email_subject = $email_subject;
        $this->reminder_type = $reminder_type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->email_subject)->markdown('emails.subscriptions.trial-end', ['user' => $this->user, 'days' => $this->days, 'reminder_type' => $this->reminder_type]);
    }
}
