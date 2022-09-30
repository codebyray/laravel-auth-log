<?php

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
