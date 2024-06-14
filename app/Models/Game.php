<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class Game
{
    public readonly int $id;
    public readonly string $name;
    public readonly string $slug;
    public readonly ?string $backgroundImage;
    public readonly ?string $released;
    public readonly ?array $platforms;
    public readonly ?array $stores;
    public readonly ?array $genres;

    public function __construct(array $data)
    {
        $this->validateData($data);
        $this->setData($data);
    }

    private function validateData(array $data): void
    {
        $validator = Validator::make($data, [
            'id'               => 'required|int',
            'name'             => 'required|string',
            'slug'             => 'required|string',
            'background_image' => 'nullable|string',
            'released'         => 'nullable|string',
            'platforms'        => 'nullable|array',
            'platforms.*'      => 'array:id,name,slug',
            'stores'           => 'nullable|array',
            'stores.*'         => 'array:id,name,slug',
            'genres'           => 'nullable|array',
            'genres.*'         => 'array:id,name,slug',
        ]);

        $validator->validate();
    }

    private function setData(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->{Str::camel($key)} = $value;
        }
    }
}
