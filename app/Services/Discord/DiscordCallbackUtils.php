<?php

namespace App\Services\Discord;

use App\Enums\Discord\ButtonStyle;
use App\Enums\Discord\ComponentType;
use App\Services\Discord\Commands\Contracts\CallbackCommandInterface;
use Illuminate\Support\Str;

trait DiscordCallbackUtils
{
    /**
     * @param string $name
     * @param string $placeholder
     * @param array $options
     * @param integer $minValues
     * @param integer $maxValues
     * @param array $defaults
     * @return array
     */
    private function makeMenuComponent(
        string $name,
        array $options,
        string $placeholder = '',
        int $minValues = 1,
        int $maxValues = -1,
        array $defaults = []
    ): array {
        return [
            'type'       => ComponentType::ActionRow,
            'components' => [
                [
                    'type'           => ComponentType::StringSelect,
                    'custom_id'      => $this->getCustomId($name),
                    'options'        => $this->makeSelectOptionStructure($options, $defaults),
                    'placeholder'    => $placeholder,
                    'min_values'     => $minValues,
                    'max_values'     => $maxValues === -1 ? count($options) : $maxValues
                ]
            ]
        ];
    }

    private function makeButtonComponent(
        string $label,
        string $name,
    ): array {
        return [
            'type'       => ComponentType::ActionRow,
            'components' => [
                [
                    'type'        => ComponentType::Button,
                    'custom_id'   => $this->getCustomId($name),
                    'label'       => strtoupper($label),
                    'style'       => ButtonStyle::Primary,
                ]
            ]
        ];
    }

    /**
     * @param string $name
     * @return string
     */
    private function getCustomId(string $componentName): string
    {
        if (!$this instanceof CallbackCommandInterface) {
            throw new \Exception('Command does not implements CallbackCommandInterface.');
        }

        return sprintf('%s_%s_%s', $this->getName(), $componentName, uniqid());
    }

    /**
     * @param string $customId
     * @return array
     */
    private function parseCustomId(string $customId): array
    {
        $customId = explode('_', $customId);

        return [
            'command'   => $customId[0],
            'component' => $customId[1],
        ];
    }

    /**
     * @param array $cases
     * @param array $defaults
     * @return array
     */
    private static function makeSelectOptionStructure(
        array $cases,
        array $defaults = []
    ): array {
        return array_map(function ($case) use ($defaults) {
            $name = strtoupper($case->name) === $case->name
                ? $case->name
                : Str::headline($case->name);

            return [
                'label' => $name,
                'value' => $case->value,
                'default' => in_array($case->value, $defaults)
            ];
        }, $cases);
    }
}
