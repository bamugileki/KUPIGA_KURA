<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        try {
            $settings = Setting::getAll();
            foreach ($settings as $key => $value) {
                config(['voting.' . $key => $value]);
            }
        } catch (\Exception $e) {
            // Table may not exist yet (first migration)
        }

        View::composer('*', function ($view) {
            $lang = Session::get('lang', config('voting.default_language', 'en'));
            $view->with('current_lang', $lang);
        });
    }
}
