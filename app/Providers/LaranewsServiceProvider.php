<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use App\Helpers\NewsApiHelper\NewsApi;


class LaranewsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('newsapi', function ($app) {
            return new NewsApi(env("NEWS_API_KEY"));
        });

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {


    }

}
