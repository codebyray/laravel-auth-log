Laravel Auth Log is a package which tracks your user's authentication information such as login/logout time, IP, Browser, Location, etc. as well as sends out notifications via mail, slack, or sms for new devices and failed logins.

## Documentation, Installation, and Usage Instructions

## Installation
Installing the package via composer:
```bash
composer require codebyray/laravel-auth-log
```
If you want to use the location features you will need to install `torann/geoip`
```bash
composer require torann/geoip
```
If you choose to install `torann/geop` you should publish the config file:
```bash
php artisan vendor:publish --provider="Torann\GeoIP\GeoIPServiceProvider" --tag=config
```
## Setup / Configuration
Publish and run the migrations:
```bash
php artisan vendor:publish --provider="Codebyray\LaravelAuthLog\LaravelAuthLogServiceProvider" --tag="auth-log-migrations"
php artisan migrate
```
Publish views and email files:
```bash
php artisan vendor:publish --provider="Codebyray\LaravelAuthLog\LaravelAuthnLogServiceProvider" --tag="auth-log-views"
```
Publish the config file:
```bash
php artisan vendor:publish --provider="Codebyray\LaravelAuthLog\LaravelAuthLogServiceProvider" --tag="auth-log-config"
```
Config file contents:
```php
return [

    /*
    |--------------------------------------------------------------------------
    | Database Table Name
    |--------------------------------------------------------------------------
    | 
    | You can change the database table name if you wish. For most cases this
    | does not need to be modified
    |
    */
    'table_name' => 'auth_log',

    /*
    |--------------------------------------------------------------------------
    | Database Connection
    |--------------------------------------------------------------------------
    | 
    | This is the connection to the database at which the auth_log table resides.
    | Leave this as null to use your applications default database connection.
    |
    */
    'db_connection' => null,

    /*
    |--------------------------------------------------------------------------
    | Events Listened For
    |--------------------------------------------------------------------------
    | 
    | These are the events this package will listen for and log.
    |
    */
    'events' => [
        'login' => \Illuminate\Auth\Events\Login::class,
        'failed' => \Illuminate\Auth\Events\Failed::class,
        'logout' => \Illuminate\Auth\Events\Logout::class,
        'logout-other-devices' => \Illuminate\Auth\Events\OtherDeviceLogout::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications Configuration
    |--------------------------------------------------------------------------
    | 
    | This is where we setup the notifications that are sent out.
    |
    | new-device
    |   enabled
    |       - If enabled is set to true, a notification will be sent when a user logs 
    |         in with a new device.
    |   location
    |       - If set to true, the location of the user will be sent with the notification.
    |         Notice: You must have installed torann/geoip for this to work.
    |   template
    |       - The notification class iused to send the notification.
    |
    | failed-login
    |   enabled
    |       - If enabled is set to true, a notification will be sent when a user login 
    |         has failed.
    |   location
    |       - If set to true, the location of the user will be sent with the notification.
    |         Notice: You must have installed torann/geoip for this to work.
    |   template
    |       - The notification class iused to send the notification.
    |
    */
    'notifications' => [
        'new-device' => [
            'enabled' => env('AUTH_LOG_NEW_DEVICE_NOTIFICATION', true),
            'location' => env('AUTH_LOG_GET_LOCATION', false),
            'template' => \Codebyray\LaravelAuthLog\Notifications\NewDevice::class,
        ],
        'failed-login' => [
            'enabled' => env('AUTH_LOG_FAILED_LOGIN_NOTIFICATION', true),
            'location' => env('AUTH_LOG_GET_LOCATION', false),
            'template' => \Codebyray\LaravelAuthLog\Notifications\FailedLogin::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Purge (Clean-up) Logs
    |--------------------------------------------------------------------------
    | purge
    | When the clean-up command is run, this will determine how old the logs must be
    | in order to be deleted. Set purge days to the number of days you wish to keep
    | the logs. If you would like to keep them indefinitly, do not schedule the clean-up
    | command to run.
    |
    */
    'purge' => 365,
];
```
## Setup The User Model
In order to log the events above you need to add the `AuthenticationLoggable` and `Notifiable` traits to your model. The `Notifiable` is normally setup when you generate a model using the `artisan make:model` command, if it does not be sure to add it.
```php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Codebyray\LaravelAuthLog\Traits\AuthenticationLoggable;

class User extends Authenticatable
{
    use Notifiable, AuthenticationLoggable;
}
```
This package will listen to Laravel's Login, Logout, Failed and OtherDeviceLogout events.
# Usage
## Getting The Logs
Get all the authentication logs for a user:
```php
$user = User::find(1);
$user->authentications;
// or
User::find(1)->authentications;
// or
auth()->user()->authentications;
```
Get the users last login information:
```php
/*
|
| Each method below returns something like for the date & time:
|   Illuminate\Support\Carbon @1664518251 {#1176
|       value: "2022-09-30 06:10:51",
|   }
|
| If requesting th IP the events will return:
|   "127.0.0.1"
|
*/
// Get the date & time of the last login whether it be a failed attempt or successful login.
User::find(1)->lastLoginAt();

// Get the date & time of the last successful login.
User::find(1)->lastSuccessfulLoginAt();

// Get the IP address of the last login whether a failed attempt or successful one.
User::find(1)->lastLoginIp();

// Get the IP address of the last successful login.
User::find(1)->lastSuccessfulLoginIp();
```
Get a users previous successful login time or IP address:
```php
// Get the date & time for the users previous successful login.
User::find(1)->previousLoginAt();

// Get the IP address for the users previous successful login.
User::find(1)->previousLoginIp();
```
In the above examples you can use `auth()->user()` to get the logs for the currently logged in user.
# Notifications
Notifications are sent out via email by default. You can sent them to be sent by 'mail', 'nexmo' and 'slack' by setting them in your 'Authenticatable' model.
To setup the channels you want notifications sent, you nee to define the 'notifyAuthenticationLogVia' method in your `Authenticatable` model.
```php
public function notifyAuthenticationLogVia()
{
    return ['nexmo', 'mail', 'slack'];
}
```
In order to use '[Slack](https://laravel.com/docs/8.x/notifications#routing-slack-notifications)' and/or '[Nexmo](https://laravel.com/docs/8.x/notifications#routing-sms-notifications)', you need to install the drivers for each and follow their documentation for setting up your 'Authenticatable' models.
## New Device Notifications
Enabled by default, they use the `\Codebyray\LaravelAuthLog\Notifications\NewDevice` class which can be overridden in the config file.
## Failed Login Notifications
Enabled by default, they use the `\Codebyray\LaravelAuthLog\Notifications\FailedLogin` class which can be overridden in the config file.
## Location
If the `torann/geoip package` is installed, you will need to enable locations via the config file. This is disabbled by default.

You can turn this on within the configuration for each template.

Note: By default when working locally, no location will be recorded because it will send back the default address from the geoip config file. You can override this behavior in the email templates.
# Purging Old Logs
You can purge the logs using the following artisian command:
```bash
php artisan auth-log:purge
```
Any records older than the specified number of days in the `config/auth-log.php` file via the `purge` setting will be deleted. Default number of days is 365.
```php
'purge' => 365,
```
You can schedule the command to run automatically every month, or however often you'd like using the following command:
```php
$schedule->command('auth-log:purge')->monthly();
```
## Version Compatibility

 Laravel  | Auth Log
:---------|:------------------
 9.x      | 1.x

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Ray "RJ"](https://github.com/codebyray)
- Original Code - [rappasoft/laravel-authentication-log](https://github.com/rappasoft/laravel-authentication-log)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
