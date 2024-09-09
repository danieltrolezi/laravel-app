<?php

namespace App\Services\Discord\Utils;

use Illuminate\Support\Arr;

trait DiscordCallbackUtils
{
    /**
     * @param string $name
     * @return string
     */
    private function formatCustomId(string $commandName, string $componentName): string
    {
        if (strpos($commandName, '_') !== false) {
            throw new \Exception('Command name can not contain underscores.');
        }

        if (strpos($componentName, '_') !== false) {
            throw new \Exception('Component name can not contain underscores.');
        }

        return sprintf('%s_%s_%s', $commandName, $componentName, uniqid());
    }

    /**
     * @param array $payload
     * @return array
     */
    private function parseCustomId(
        array $payload,
        string $path = 'data.custom_id'
    ): array {
        $customId = explode(
            '_',
            Arr::get($payload, $path)
        );

        return [
            'command'   => $customId[0],
            'component' => $customId[1],
            'uid'       => $customId[2],
        ];
    }
}
