<?php

namespace Codebyray\LaravelAuthLog;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\OtherDeviceLogout;
use Illuminate\Contracts\Events\Dispatcher;
use Codebyray\LaravelAuthLog\Commands\PurgeAuthenticationLogCommand;
use Codebyray\LaravelAuthLog\Listeners\FailedLoginListener;
use Codebyray\LaravelAuthLog\Listeners\LoginListener;
use Codebyray\LaravelAuthLog\Listeners\LogoutListener;
use Codebyray\LaravelAuthLog\Listeners\OtherDeviceLogoutListener;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelAuthLogServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-auth-log')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasViews()
            ->hasMigration('create_auth_log_table')
            ->hasCommand(PurgeAuthenticationLogCommand::class);

        $events = $this->app->make(Dispatcher::class);
        $events->listen(config('auth-log.events.login', Login::class), LoginListener::class);
        $events->listen(config('auth-log.events.failed', Failed::class), FailedLoginListener::class);
        $events->listen(config('auth-log.events.logout', Logout::class), LogoutListener::class);
        $events->listen(config('auth-log.events.other-device-logout', OtherDeviceLogout::class), OtherDeviceLogoutListener::class);
    }
}
