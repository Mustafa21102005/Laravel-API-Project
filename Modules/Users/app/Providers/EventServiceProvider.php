<?php

namespace Modules\Users\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Users\Events\VerifyEmailByCode;
use Modules\Users\Events\VerifyPhoneByCode;
use Modules\Users\Listeners\VerifyEmailByCodeFired;
use Modules\Users\Listeners\VerifyPhoneByCodeFired;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        VerifyEmailByCode::class => [
            VerifyEmailByCodeFired::class,
        ],
        VerifyPhoneByCode::class => [
            VerifyPhoneByCodeFired::class,
        ],
    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void {}
}
