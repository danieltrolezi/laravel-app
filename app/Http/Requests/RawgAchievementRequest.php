<?php

namespace App\Http\Requests;

use App\Enums\RawgField;
use App\Enums\SortOrder;
use Illuminate\Foundation\Http\FormRequest;

class RawgAchievementRequest extends FormRequest
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
            'order_by'                 => 'nullable|string',
            'sort_order'               => 'nullable|string|in:' . SortOrder::valuesAsString(),
            RawgField::PageSize->value => 'nullable|int',
            RawgField::Page->value     => 'nullable|int'
        ];
    }
}
