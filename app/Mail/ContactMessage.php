<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $contactData;

    public function __construct(array $contactData)
    {
        $this->contactData = $contactData;
    }

    public function build()
    {
        return $this->subject('EasyCPRSLabs Contact: '.$this->contactData['subject'])
            ->replyTo($this->contactData['email'], $this->contactData['name'])
            ->view('emails.contact-message')
            ->with('contactData', $this->contactData);
    }
}
