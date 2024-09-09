<?php

namespace App\Http\Requests;

use App\Enums\Discord\ComponentType;
use App\Enums\Discord\InteractionType;
use Illuminate\Foundation\Http\FormRequest;
use Override;

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
        $rules = $this->getRulesForInteractionType(
            InteractionType::from($this->get('type'))
        );

        return array_merge([
            'type'                 => 'required|integer|in:' . InteractionType::valuesAsString(),
            'data'                 => 'required|array',
            'data.options'         => 'sometimes|array',
            'data.options.*'       => 'required|array',
            'data.options.*.value' => 'required',
            'user'                 => 'required|array',
            'user.id'              => 'required|string',
            'user.username'        => 'required|string',
            'user.global_name'     => 'required|string',
            'channel.id'           => 'required|string',
            'message.components'   => 'sometimes|array'
        ], $rules);
    }

    /**
     * @param InteractionType $type
     * @return array
     */
    private function getRulesForInteractionType(InteractionType $type): array
    {
        return match ($type) {
            InteractionType::Command => [
                'data.type' => 'required|int',
                'data.name' => 'required|string',
            ],
            InteractionType::MessageComponent => [
                'data.component_type'      => 'required|int|in:' . ComponentType::valuesAsString(),
                'data.custom_id'           => 'required|string',
                'data.values'              => 'sometimes|array',
            ],
            default => []
        };
    }

    #[Override]
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated($key, $default);
        $validated['type'] = InteractionType::from($validated['type']);

        return $validated;
    }
}
