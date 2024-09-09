<?php

namespace App\Services\Discord\Utils;

trait DiscordEmbedUtils
{
    /**
     * @param string $title
     * @param string $description
     * @param string $url
     * @param string $color
     * @param array $fields
     * @param string $image
     * @return array
     */
    private function makeEmbed(
        string $title,
        string $description,
        string $url,
        array $fields = [],
        ?string $color = null,
        ?string $image = null
    ): array {
        if (empty($color)) {
            $color = config('discord.colors.primary');
        }

        $embed = [
            'title'       => $title,
            'description' => $description,
            'url'         => $url,
            'color'       => hexdec($color),
            'fields'      => $this->makeEmbedFields($fields),
        ];

        if (!empty($image)) {
            $embed['image']['url'] = $image;
        }

        return $embed;
    }

    /**
     * @param array $fields
     * @param boolean $inline
     * @return array
     */
    private function makeEmbedFields(
        array $fields,
        bool $inline = true
    ): array {
        $embedFields = [];

        foreach ($fields as $name => $value) {
            $embedFields[] = [
                'name'   => $name,
                'value'  => $value,
                'inline' => $inline
            ];
        }

        return $embedFields;
    }
}
