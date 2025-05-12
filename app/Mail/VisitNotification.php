<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VisitNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $visitor;

    /**
     * Create a new message instance.
     */
    public function __construct($visitor)
    {
        $this->visitor = $visitor;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Visit Confirmation')
                    ->view('emails.visit_notification')
                    ->with([
                        'visitor' => $this->visitor
                    ]);
    }
}
