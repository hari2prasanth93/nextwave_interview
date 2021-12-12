<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Firebase\JWT\JWT;
class AuthServiceProvider extends ServiceProvider
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
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            $jwt_token=$request->header('credentials');
            if($jwt_token!=null){
            $key = env("JWT_SECRET_KEY");
            $jwt_token_decoded = JWT::decode($jwt_token,$key,['HS256']);

            if(isset($jwt_token_decoded->user_id)){
                return User::find($jwt_token_decoded->user_id);
            }
            }
        });
    }
}
