<?php

$constants = [
    'APP_NAME'    => env('APP_NAME', 'Laravel App'),
    'APP_VERSION' => env('APP_VERSION', '1.0'),
    'APP_URL'     => env('APP_URL', 'laravel-app.local')
];

foreach ($constants as $key => $value) {
    if(!defined($key)) {
        define($key, $value);
    }
}