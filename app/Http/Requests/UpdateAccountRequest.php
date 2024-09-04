<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Override;

class UpdateAccountRequest extends FormRequest
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
            'name'            => 'sometimes|string|min:5|max:255',
            'email'           => ['sometimes', 'email', Rule::unique('users', 'email')->ignore($this->user())],
            'username'        => 'sometimes|string|min:5|max:255',
            'password'        => 'sometimes|string|min:6|max:18',
            'discord_user_id' => ['sometimes', 'string', Rule::unique('users', 'username')->ignore($this->user())]
        ];
    }

    /**
     * @param [type] $keys
     * @return array
     */
    #[Override]
    public function all($keys = null): array
    {
        $all = parent::all();

        if (!Auth::user()->isRoot()) {
            Arr::forget($all, 'discord_user_id');
        }

        return $all;
    }
}
