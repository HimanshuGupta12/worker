<?php

namespace App\Services;

class EmailHtml
{
    private string $html = '';

    public function line(string $text): self
    {
        $text = e($text);
        $this->html .= "<p>$text</p>";
        return $this;
    }

    public function image(string $url): self
    {
        $this->html .= '<p style="text-align: center;"><img src="' . e($url) . '" style="width: 200px; height: 200px;"></p>';
        return $this;
    }

    public function button(string $url, string $text): self
    {
        $title_in_uppercase = e(strtoupper($text));

        $this->html .= <<<TEXT
<p style="margin: 40px 0; text-align: center;">
    <a target="_blank" style="padding: 15px 25px; background-color: #5d21d2 ; border: none; border-radius: 3px; color: #fff; font-size: 20px; font-weight: 600; text-decoration: none; box-shadow: 0 3px 0 0 rgb(110,58,210); " href="{$url}">{$title_in_uppercase}</a>
</p>
TEXT;
        return $this;
    }

    public function render(): string
    {
        return view('emails.email_html', ['html' => $this->html])->render();
    }
}
