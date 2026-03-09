<?php

namespace Modules\Users\Listeners;

use Illuminate\Support\Facades\Mail;
use Modules\Users\Events\VerifyEmailByCode;
use Modules\Users\Mail\SendActivationCode;

class VerifyEmailByCodeFired
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(VerifyEmailByCode $event): void
    {
        Mail::to($event->user->email)->send(new SendActivationCode(
            __(
                'main.activate_account',
                ['type' => __('main.email')]
            ),
            __('main.email_code_msg', ['code' => $event->user->email_code, 'name' => $event->user->name])
        ));
    }
}
