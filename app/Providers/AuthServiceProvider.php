<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //Add a check for verified emails before giving out oauth token.
//        Route::group(['middleware' => 'checkVerifiedUser'], function(){
            Passport::routes(); // <-- Replace this with your own version
//        });

//        If we need shorter/longer access tokens.
//        Passport::tokensExpireIn(now()->addDays(15));
//
//        Passport::refreshTokensExpireIn(now()->addDays(30));
        //
    }
}
