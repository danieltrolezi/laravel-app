<?php

function loadEnv($path)
{
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            $value = trim($value, '"');
            putenv("$key=$value");
        }
    }
}

loadEnv(__DIR__ . '/.env');

$constants = [
    'APP_NAME'    => getenv('APP_NAME') ?: 'Laravel App',
    'APP_VERSION' => getenv('APP_VERSION') ?: '1.0',
    'APP_URL'     => getenv('APP_URL') ?: 'http://laravel-app.local'
];

foreach ($constants as $key => $value) {
    if(!defined($key)) {
        define($key, $value);
    }
}