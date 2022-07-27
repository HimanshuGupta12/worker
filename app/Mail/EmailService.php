<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailService extends Mailable
{
    use Queueable, SerializesModels;

    public $view;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $view, array $data)
    {
        $this->view = $view;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view($this->view, $this->data);
    }
}
