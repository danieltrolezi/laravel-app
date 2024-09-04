<?php

use App\Enums\Discord\CommandType;
use App\Enums\Frequency;
use App\Enums\Period;

return [

    /*
    |--------------------------------------------------------------------------
    | Discord
    |--------------------------------------------------------------------------
    |
    | List of Application Commands that can be registered on Discord.
    | 
    | More info:
    | - Commands: https://discord.com/developers/docs/interactions/application-commands
    | - Interactions: https://discord.com/developers/docs/interactions/receiving-and-responding
    |
    */

    'commands' => [
        [
            'name'        => 'settings',
            'description' => 'Set your preferences for game notifications',
            'options' => [
                [
                    'type'        => CommandType::String->value,
                    'name'        => 'platforms',
                    'description' => 'Enter the platforms for game releases (comma-separated)',
                    'required'    => true,
                ],
                [
                    'type'         => CommandType::String->value,
                    'name'         => 'genres',
                    'description'  => 'Choose the genres for game releases (comma-separated)',
                    'required'     => true
                ],
                [
                    'type'        => CommandType::String->value,
                    'name'        => 'period',
                    'description' => 'Choose the period for notifications',
                    'required'    => true,
                    'choices'     => Period::casesAsArray()
                ],
                [
                    'type'        => CommandType::String->value,
                    'name'        => 'frequency',
                    'description' => 'Choose how often you want notifications',
                    'required'    => true,
                    'choices'     => Frequency::casesAsArray()
                ],
            ]
        ]
    ]

];
