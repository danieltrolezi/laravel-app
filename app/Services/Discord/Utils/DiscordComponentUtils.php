<?php

namespace App\Services\Discord\Utils;

use App\Enums\Discord\ButtonStyle;
use App\Enums\Discord\ComponentType;
use App\Models\PaginatedResponse;
use App\Services\Discord\Commands\Contracts\CallbackCommandInterface;
use Illuminate\Support\Str;

trait DiscordComponentUtils
{
    use DiscordCallbackUtils;

    private const string COMPONENT_GAMES_FOUND = 'games-fround';
    private const string COMPONENT_CURRENT_PAGE = 'current-page-';
    private const string COMPONENT_NEXT_PAGE = 'next-page';
    private const string COMPONENT_PREV_PAGE = 'previous-page';

    private function makeCustomId(string $componentName): string
    {
        if (!$this instanceof CallbackCommandInterface) {
            throw new \Exception('Command does not implements CallbackCommandInterface.');
        }

        return $this->formatCustomId($this->getCommandName(), $componentName);
    }

    /**
     * @return array
     */
    private function makeActionRow(array $components): array
    {
        return [
            'type'       => ComponentType::ActionRow,
            'components' => $components
        ];
    }

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
            'type'           => ComponentType::StringSelect,
            'custom_id'      => $this->makeCustomId($name),
            'options'        => $this->makeSelectOptions($options, $defaults),
            'placeholder'    => $placeholder,
            'min_values'     => $minValues,
            'max_values'     => $maxValues === -1 ? count($options) : $maxValues
        ];
    }

    /**
     * @param array $cases
     * @param array $defaults
     * @return array
     */
    private static function makeSelectOptions(
        array $cases,
        array $defaults = []
    ): array {
        return array_map(function ($case) use ($defaults) {
            $name = strtoupper($case->name) === $case->name
                ? $case->name
                : Str::headline($case->name);

            return [
                'label'   => $name,
                'value'   => $case->value,
                'default' => in_array($case->value, $defaults)
            ];
        }, $cases);
    }

    /**
     * @param string $label
     * @param string $name
     * @param [type] $style
     * @param boolean $disabled
     * @param string $emoji
     * @return array
     */
    private function makeButtonComponent(
        string $label,
        string $name,
        ButtonStyle $style = ButtonStyle::Primary,
        bool $disabled = false,
        string $emoji = ''
    ): array {
        $button = [
            'type'        => ComponentType::Button,
            'custom_id'   => $this->makeCustomId($name),
            'label'       => strtoupper($label),
            'style'       => $style,
            'disabled'    => $disabled
        ];

        if (!empty($emoji)) {
            $button['emoji'] = [
                'name' => $emoji
            ];
        }

        return $button;
    }

    /**
     * @param PaginatedResponse $response
     * @return array
     */
    private function makePaginationComponents(PaginatedResponse $response): array
    {
        return [
            $this->makeButtonComponent(
                name: self::COMPONENT_GAMES_FOUND,
                label: sprintf('Found %s game(s)', $response->total),
                style: ButtonStyle::Secundary,
                disabled: true,
                emoji: '🔥'
            ),
            $this->makeButtonComponent(
                name: self::COMPONENT_CURRENT_PAGE . $response->currentPage,
                label: sprintf('Page %s of %s', $response->currentPage, $response->lastPage),
                style: ButtonStyle::Secundary,
                disabled: true,
                emoji: '📖'
            ),
            $this->makeButtonComponent(
                name: self::COMPONENT_PREV_PAGE,
                label: 'Previous Page',
                disabled: $response->currentPage === 1,
            ),
            $this->makeButtonComponent(
                name: self::COMPONENT_NEXT_PAGE,
                label: 'Next Page',
                disabled: $response->currentPage === $response->lastPage,
            )
        ];
    }

    /**
     * @param array $payload
     * @return integer
     */
    private function getCurrentPage(array $payload): int
    {
        $path = 'message.components.0.components.1.custom_id';
        $customId = $this->parseCustomId($payload, $path);

        return str_replace(self::COMPONENT_CURRENT_PAGE, '', $customId['component']);
    }
}
