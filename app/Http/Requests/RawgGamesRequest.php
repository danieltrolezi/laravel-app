<?php

namespace App\Http\Requests;

use App\Enums\Platform;
use App\Enums\Rawg\RawgGenre;
use App\Enums\Rawg\RawgField;
use App\Rules\KeywordList;
use Illuminate\Foundation\Http\FormRequest;

class RawgGamesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            RawgField::Genres->value    => [
                'nullable',
                'string',
                new KeywordList(RawgGenre::values())
            ],
            RawgField::Platforms->value => [
                'nullable',
                'string',
                new KeywordList(Platform::values())
            ],
            RawgField::Ordering->value  => ['nullable', 'string'],
            RawgField::PageSize->value  => ['nullable', 'int'],
            RawgField::Page->value      => ['nullable', 'int']
        ];
    }
}
