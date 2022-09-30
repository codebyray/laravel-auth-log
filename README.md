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
    // The database table name
    // You can change this if the database keys get too long for your driver
    'table_name' => 'auth_log',

    // The database connection where the authentication_log table resides. Leave empty to use the default
    'db_connection' => null,

    // The events the package listens for to log
    'events' => [
        'login' => \Illuminate\Auth\Events\Login::class,
        'failed' => \Illuminate\Auth\Events\Failed::class,
        'logout' => \Illuminate\Auth\Events\Logout::class,
        'logout-other-devices' => \Illuminate\Auth\Events\OtherDeviceLogout::class,
    ],

    'notifications' => [
        'new-device' => [
            // Send the NewDevice notification
            'enabled' => env('AUTH_LOG_NEW_DEVICE_NOTIFICATION', true),

            // Use torann/geoip to attempt to get a location
            'location' => env('AUTH_LOG_GET_LOCATION', true),

            // The Notification class to send
            'template' => \Codebyray\LaravelAuthLog\Notifications\NewDevice::class,
        ],
        'failed-login' => [
            // Send the FailedLogin notification
            'enabled' => env('AUTH_LOG_FAILED_LOGIN_NOTIFICATION', false),

            // Use torann/geoip to attempt to get a location
            'location' => env('AUTH_LOG_GET_LOCATION', true),

            // The Notification class to send
            'template' => \Codebyray\LaravelAuthLog\Notifications\FailedLogin::class,
        ],
    ],

    // When the clean-up command is run, delete old logs greater than `purge` days
    // Don't schedule the clean-up command if you want to keep logs forever.
    'purge' => 365,
];
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

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Ray "RJ"](https://github.com/codebyray)
- Original Code - [rappasoft/laravel-authentication-log](https://github.com/rappasoft/laravel-authentication-log)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
