<?php

namespace Modules\Users\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Users\Events\VerifyPhoneByCode;
use Twilio\Rest\Client;

class VerifyPhoneByCodeFired implements ShouldQueue
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
    public function handle(VerifyPhoneByCode $event): void
    {
        $user = $event->user;
        $client = new Client(getenv('TWILIO_SID'), getenv('TWILIO_AUTH_TOKEN'));
        $client->messages->create('+966' . $user->phone, ['from' => getenv('TWILIO_NUMBER'), 'body' => __('main.phone_code_msg', ['code' => $user->phone_code, 'name' => $user->name])]);
    }
}
