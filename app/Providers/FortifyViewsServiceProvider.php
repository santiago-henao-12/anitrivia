<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Fortify;

class FortifyViewsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * Sets up the views necessary for Laravel Fortify to work
     */
    public function boot(): void
    {
        // Login and register
        Fortify::loginView(function () {
            return Inertia::render('Auth/Login', [
                'canResetPassword' => Route::has('password.request'),
                'status' => session('status'),
            ]);
        });

        Fortify::registerView(function () {
            return Inertia::render('Auth/Register');
        });

        // Password reset
        Fortify::requestPasswordResetLinkView(function () {
            return Inertia::render('Auth/ForgotPassword', [
                'status' => session('status'),
            ]);
        });

        Fortify::resetPasswordView(function (Request $request) {
            return Inertia::render('Auth/ResetPassword', [
                'email' => $request->query('email'),
                'token' => $request->token,
            ]);
        });

        // Email verification
        Fortify::verifyEmailView(function () {
            return Inertia::render('Auth/VerifyEmail', [
                'status' => session('status'),
            ]);
        });

        // Password confirmation
        Fortify::confirmPasswordView(function () {
            return Inertia::render('Auth/ConfirmPassword', [
                'status' => session('status'),
            ]);
        });
    }
}
