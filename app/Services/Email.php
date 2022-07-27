<?php

namespace App\Services;

use App\Mail\EmailService;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class Email
{
    private $to;
    private $subject;
    private $text;
    private $view = null;
    private $data = [];
    private $from = null;
    private $replyTo = null;
    private $locale;
    private $later;
    private $email_html;
    private $email_html_used = false;

    public function __construct()
    {
        $this->email_html = new \App\Services\EmailHtml();
    }

    public function to(User|string $user)
    {
        $this->to = $user;
        return $this;
    }

    public function from(string $email, string $name = null)
    {
        $this->from = [$email, $name];
        return $this;
    }

    public function replyTo(string $email)
    {
        $this->replyTo = $email;
        return $this;
    }

    public function subject(string $text)
    {
        $this->subject = $text;
        return $this;
    }

    public function text(string $text)
    {
        $this->text = $text;
        return $this;
    }

    public function view(string $view, array $data = [])
    {
        $this->view = $view;
        $this->data = $data;
        return $this;
    }

    // for simple one liner
    // html is styled
    public function subjectAndText(string $text)
    {
        $this->subject($text);
        $html = emailHtml()->line($text)->render();
        $this->html($html);
        return $this;
    }

    public function line(string $text)
    {
        $this->email_html->line($text);
        $this->email_html_used = true;
        return $this;
    }

    public function image(string $url)
    {
        $this->email_html->image($url);
        $this->email_html_used = true;
        return $this;
    }

    public function button(string $url, string $title)
    {
        $this->email_html->button($url, $title);
        $this->email_html_used = true;
        return $this;
    }

    public function html(string $html)
    {
        $this->view('emails.blank', ['content' => $html]);
        return $this;
    }

    public function send()
    {
        $mailable = $this->mailable();

        $mail = Mail::to($this->to);
        $mail->send($mailable);
    }

    public function queue(string $name = 'default')
    {
        $mailable = $this->mailable($name);

        $mail = Mail::to($this->to);
        $mail->queue($mailable);
    }

    public function render()
    {
        return $this->mailable()->render();
    }

    private function mailable(string $queue = null)
    {
        if (!$this->to || !$this->subject || (!$this->text && !$this->view && !$this->email_html_used)) {
            throw new \Exception('missing message parameters');
        }

        if ($this->email_html_used) {
            $this->html($this->email_html->render());
        }

        if ($this->text) {
            $view = 'emails.blank';
            $data = ['content' => $this->text];
        } elseif ($this->view) {
            $view = $this->view;
            $data = $this->data;
        } else {
            throw new \Exception('No text or view');
        }

        $mailable = (new EmailService($view, $data))->onQueue($queue)->subject($this->subject);
        if ($this->from) {
            $mailable->from($this->from[0], $this->from[1]);
        }
        if ($this->replyTo) {
            $mailable->replyTo($this->replyTo);
        }
        if ($this->locale) {
            $mailable->locale($this->locale);
        }
        if ($this->later) {
            $mailable->delay($this->later);
        }
        return $mailable;
    }

	public function locale(string $locale)
	{
		$this->locale = $locale;
		return $this;
	}

	public function later($delay)
	{
		$this->later = $delay;
		return $this;
	}
}
