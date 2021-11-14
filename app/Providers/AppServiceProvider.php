<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    //herokuにデプロイする際にMySQLの文字数でエラーにならないようにvarchar型の文字数を191に制限
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
