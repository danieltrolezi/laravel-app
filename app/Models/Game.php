<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

#[OA\Schema()]
class Game
{
    use HasFactory;

    public readonly int $id;
    public readonly string $name;
    public readonly string $slug;
    public readonly ?string $backgroundImage;
    public readonly ?string $released;
    public readonly ?array $platforms;
    public readonly ?array $stores;
    public readonly ?array $genres;

    #[OA\Property(property: 'id', type: 'integer')]
    #[OA\Property(property: 'name', type: 'string')]
    #[OA\Property(property: 'slug', type: 'string')]
    #[OA\Property(property: 'background_image', type: 'string')]
    #[OA\Property(property: 'released', type: 'string')]
    #[OA\Property(property: 'platforms', type: 'array', items: new OA\Items(
        type: 'object',
        properties: [
            new OA\Property(
                property: "id",
                type: "integer"
            ),
            new OA\Property(
                property: "name",
                type: "string"
            ),
            new OA\Property(
                property: "slug",
                type: "string"
            ),
        ]
    ))]
    #[OA\Property(property: 'stores', type: 'array', items: new OA\Items(type: 'object'))]
    #[OA\Property(property: 'genres', type: 'array', items: new OA\Items(type: 'object'))]
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
            'platforms.*.id'   => 'required|int',
            'platforms.*.name' => 'required|string',
            'platforms.*.slug' => 'required|string',
            'stores'           => 'nullable|array',
            'stores.*.id'      => 'required|int',
            'stores.*.name'    => 'required|string',
            'stores.*.slug'    => 'required|string',
            'genres'           => 'nullable|array',
            'genres.*.id'      => 'required|int',
            'genres.*.name'    => 'required|string',
            'genres.*.slug'    => 'required|string',
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
