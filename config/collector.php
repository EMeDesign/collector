<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Settings About Collector Application
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for this application
    |
    */

    'owner' => [
        'username' => env(key: 'OWNER_DEFAULT_USERNAME', default: 'Owner'),
        'password' => env(key: 'OWNER_DEFAULT_PASSWORD', default: 'DB+vSw3SFUJI52*U'),
        'email'    => env(key: 'OWNER_DEFAULT_EMAIL', default: 'owner@example.com'),
    ],

];
