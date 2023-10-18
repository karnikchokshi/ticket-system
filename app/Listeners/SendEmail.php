<?php

namespace App\Listeners;

use App\Events\EmailSent;
use App\Mail\EmailMailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(EmailSent $event): void
    {
        Mail::to($event->user)->send(new EmailMailable($event->message));
    }
}
