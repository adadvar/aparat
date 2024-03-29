<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Channel;
use App\Models\Comment;
use App\Models\Playlist;
use App\Models\User;
use App\Models\Video;
use FFMpeg\Filters\Video\RotateFilter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        $this->registerModelBindings();
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            // return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    private function registerModelBindings(){
        Route::model('video', Video::class);
        Route::model('channel', Channel::class);
        Route::model('comment', Comment::class);
        Route::model('playlist', Playlist::class);
        Route::model('category', Category::class);
        Route::model('user', User::class);
    }
}
