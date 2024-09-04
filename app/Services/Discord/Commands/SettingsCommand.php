<?php

namespace App\Services\Discord\Commands;

use App\Enums\Frequency;
use App\Enums\Period;
use App\Enums\Platform;
use App\Enums\Rawg\RawgGenre;
use App\Repositories\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SettingsCommand implements CommandInterface
{
    /**
     * @param UserRepository $userRepository
     */
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    /**
     * @param array $payload
     * @return array
     */
    public function exec(array $payload): array
    {
        $user = $this->userRepository->findByDiscordId(
            $payload['user']['id']
        );

        $settings = [
            'platforms' => explode(',', Arr::get($payload, 'data.options.0.value')),
            'genres'    => explode(',', Arr::get($payload, 'data.options.1.value')),
            'period'    => Arr::get($payload, 'data.options.2.value'),
            'frequency' => Arr::get($payload, 'data.options.3.value')
        ];

        try {
            $this->validateSettings($settings);
        } catch (ValidationException $e) {
            return [
                'content' => $e->validator->errors()->first()
            ];
        }

        if ($user) {
            $this->userRepository->updateSettings($user, $settings);
        } else {
            $this->userRepository->createFromDiscord([
                'name'            => $payload['user']['global_name'],
                'username'        => $payload['user']['username'],
                'discord_user_id' => $payload['user']['id']
            ], $settings);
        }

        return [
            'content' => 'Your preferences have been updated!'
        ];
    }

    /**
     * @param array $settings
     * @return void
     */
    private function validateSettings(array $settings)
    {
        Validator::make($settings, [
            'platforms'       => ['required', 'array'],
            'platforms.*'     => ['required', 'string', 'in:' . Platform::valuesAsString()],
            'genres'          => ['required', 'array'],
            'genres.*'        => ['required', 'string', 'in:' . RawgGenre::valuesAsString()],
            'period'          => ['required', 'string', 'in:' . Period::valuesAsString()],
            'frequency'       => ['required', 'string', 'in:' . Frequency::valuesAsString()],
        ])->validate();
    }
}
