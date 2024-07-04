<?php

namespace App\Http\Requests;

use App\Enums\Platform;
use App\Enums\RawgGenre;
use App\Enums\RawgField;
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
                $this->keywordListRule(RawgGenre::valuesAsString('|'))
            ],
            RawgField::Platforms->value => [
                'nullable',
                'string', $this->keywordListRule(Platform::valuesAsString('|'))
            ],
            RawgField::Ordering->value  => ['nullable', 'string'],
            RawgField::PageSize->value  => ['nullable', 'int'],
            RawgField::Page->value      => ['nullable', 'int']
        ];
    }

    /**
     * @param string $string
     * @return string
     */
    private function keywordListRule(string $string): string
    {
        return "regex:/^(?<keywords>($string)+)(,(?&keywords))*$/i";
    }
}
