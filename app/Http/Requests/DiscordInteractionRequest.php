<?php

namespace App\Http\Requests;

use App\Enums\Discord\InteractionType;
use Illuminate\Foundation\Http\FormRequest;

class DiscordInteractionRequest extends FormRequest
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
        $additional = [];

        if ($this->get('type') == InteractionType::Command->value) {
            $additional = [
                'user'                 => 'required|array',
                'user.id'              => 'required|string',
                'user.username'        => 'required|string',
                'user.global_name'     => 'required|string',
                'data'                 => 'required|array',
                'data.name'            => 'required|string',
                'data.options'         => 'required|array',
                'data.options.*'       => 'required|array',
                'data.options.*.value' => 'required',
            ];
        }

        return array_merge([
            'type' => 'required|integer|in:' . InteractionType::valuesAsString(),
        ], $additional);
    }
}
