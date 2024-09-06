<?php

use App\Enums\Discord\OptionType;
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
        ],
        [
            'name'        => 'releases',
            'description' => 'List upcoming game releases',
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
            'description' => 'Clear channel messages'
        ],
        [
            'name'        => 'help',
            'description' => 'Need help?',
        ]
    ]

];
