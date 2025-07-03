<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Reward;
use App\Models\Tutorial;
use App\Observers\TutorialObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    // Untuk view share
    View::composer('*', function ($view) {
        $user = Auth::user();
        $points = 0;

        if ($user) {
            $points = \App\Models\Reward::where('user_id', $user->id)->sum('poin');
        }

        $view->with('user', $user);
        $view->with('points', $points);
    });

    // Pasang observer
    \App\Models\Tutorial::observe(\App\Observers\TutorialObserver::class);
}

}
