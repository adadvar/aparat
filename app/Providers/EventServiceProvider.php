<?php

namespace App\Providers;

use App\Events\ActiveUnregisteredUser;
use App\Events\DeleteVideo;
use App\Events\UploadNewVideo;
use App\Events\VisitVideo;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Laravel\Passport\Events\AccessTokenCreated;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UploadNewVideo::class => [
            'App\Listeners\ProcessUploadedVideo'
        ],
        DeleteVideo::class => [
            'App\Listeners\DeleteVideoData'
        ],
        VisitVideo::class => [
            'App\Listeners\AddVisitedVideoLogToVideoViewsTable'
        ],
        
        AccessTokenCreated::class => [
            'App\Listeners\ActiveUnregisteredUserAfterLogin'
        ], 

        ActiveUnregisteredUser::class => [

        ], 

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
