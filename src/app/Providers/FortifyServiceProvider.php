<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Validation\ValidationException;



class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \Laravel\Fortify\Contracts\RegisterResponse::class,
            \App\Actions\Fortify\CustomRegisterResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email.$request->ip());
        });
        \Laravel\Fortify\Fortify::ignoreRoutes(false);
        Fortify::registerView(fn() => view('register'));
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::loginView(fn() => view('login'));

        Fortify::authenticateUsing(function (\App\Http\Requests\LoginRequest $request) {
            $credentials = $request->validated();

            $user = \App\Models\User::where('email', $credentials['email'])->first();

            if (! $user || ! \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['ログイン情報が登録されていません'],
                ]);
            }

            return $user;
        });
    }
}
