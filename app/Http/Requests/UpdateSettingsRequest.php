<?php

namespace App\Http\Requests;

use App\Enums\Frequency;
use App\Enums\Period;
use App\Enums\Platform;
use App\Enums\RawgGenre;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
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
            'platforms'    => ['sometimes', 'array'],
            'platforms.*'  => ['required', 'string', 'in:' . Platform::valuesAsString()],
            'genres'       => ['sometimes', 'array'],
            'genres.*'     => ['required', 'string', 'in:' . RawgGenre::valuesAsString()],
            'period'       => ['sometimes', 'string', 'in:' . Period::valuesAsString()],
            'frequency'    => ['sometimes', 'string', 'in:' . Frequency::valuesAsString()],
        ];
    }
}
