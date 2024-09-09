<?php

use App\Enums\Discord\OptionType;
use App\Enums\Period;
use App\Services\Discord\Commands\ClearCommand;
use App\Services\Discord\Commands\HelpCommand;
use App\Services\Discord\Commands\ReleasesCommand;
use App\Services\Discord\Commands\SettingsCommand;

return [

    /*
    |--------------------------------------------------------------------------
    | Discord
    |--------------------------------------------------------------------------
    |
    | List of Application Commands that can be registered on Discord and other
    | configurations.
    | 
    | More info:
    | - Commands: https://discord.com/developers/docs/interactions/application-commands
    | - Interactions: https://discord.com/developers/docs/interactions/receiving-and-responding
    |
    */

    'colors' => [
        'primary' => '7332C7'
    ],

    'commands' => [
        [
            'name'        => 'settings',
            'description' => 'Set your preferences for game releases and notifications',
        ],
        [
            'name'        => 'releases',
            'description' => 'List upcoming game releases based on your preferences',
            'options' => [
                [
                    'type'        => OptionType::String->value,
                    'name'        => 'period',
                    'description' => 'Choose the period',
                    'required'    => false,
                    'choices'     => Period::friendlyCases()
                ],
            ]
        ],
        [
            'name'        => 'clear',
            'description' => 'Clear Bot messages',
        ],
        [
            'name'        => 'help',
            'description' => 'Show help message',
        ]
    ]

];
