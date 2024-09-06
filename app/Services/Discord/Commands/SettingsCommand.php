<?php

namespace App\Services\Discord\Commands;

use App\Enums\Frequency;
use App\Enums\Period;
use App\Enums\Platform;
use App\Enums\Rawg\RawgGenre;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Discord\Commands\Contracts\CallbackCommandInterface;
use App\Services\Discord\DiscordCallbackUtils;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SettingsCommand implements CallbackCommandInterface
{
    use DiscordCallbackUtils;

    private const string COMPONENT_PLATFORMS = 'platforms';
    private const string COMPONENT_GENRES = 'genres';
    private const string COMPONENT_PERIOD = 'period';
    private const string COMPONENT_FREQUENCY = 'frequency';

    private User $user;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(
        private UserRepository $userRepository
    ) {
        $this->user = Auth::user();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'settings';
    }

    /**
     * @param array $payload
     * @return array
     */
    public function exec(array $payload): array
    {
        return [
            'content' => 'Select your preferred platforms:',
            'components' => [
                $this->makeMenuComponent(
                    name: self::COMPONENT_PLATFORMS,
                    options: Platform::cases(),
                    defaults: $this->user->settings->platforms
                ),
                $this->makeButtonComponent(
                    label: 'Next',
                    name: self::COMPONENT_PLATFORMS
                )
            ]
        ];
    }

    /**
     * @param array $payload
     * @return array
     */
    public function callback(array $payload): array
    {
        $customId = $this->parseCustomId(
            $payload['data']['custom_id']
        );

        $values = Arr::get($payload, 'data.values', []);

        if (!empty($values)) {
            $values = $this->prepValues($customId['component'], $values);

            try {
                $this->validateSettings($values);
                $this->userRepository->updateSettings($this->user, $values);
            } catch (ValidationException $e) {
                return $this->makeError($e);
            }
        }

        $handler = 'handle' . Str::studly($customId['component']);
        return call_user_func([$this, $handler], $values);
    }

    /**
     * @param string $component
     * @param array $values
     * @return array
     */
    private function prepValues(string $component, array $values): array
    {
        switch ($component) {
            case self::COMPONENT_PERIOD:
            case self::COMPONENT_FREQUENCY:
                return [$component => $values[0]];

            default:
                return [$component => $values];
        }
    }

    /**
     * @param array $settings
     * @return void
     */
    private function validateSettings(array $settings)
    {
        Validator::make(
            $settings,
            [
                'platforms'       => ['sometimes', 'array'],
                'platforms.*'     => ['required', 'string', 'in:' . Platform::valuesAsString()],
                'genres'          => ['sometimes', 'array'],
                'genres.*'        => ['required', 'string', 'in:' . RawgGenre::valuesAsString()],
                'period'          => ['sometimes', 'string', 'in:' . Period::valuesAsString()],
                'frequency'       => ['sometimes', 'string', 'in:' . Frequency::valuesAsString()],
            ],
            [
                'platforms.*.in' => 'Invalid platform. Pick one or many: ' . Platform::valuesAsString(),
                'genres.*.in'    => 'Invalid genre. Pick one or many: ' . RawgGenre::valuesAsString()
            ]
        )->validate();
    }

    /**
     * @param ValidationException $e
     * @return array
     */
    private function makeError(ValidationException $e): array
    {
        return [
            'content' => $e->validator->errors()->first()
        ];
    }

    /**
     * @return array
     */
    private function handlePlatforms(array $values = []): array
    {
        return [
            'content' => 'Select your preferred genres:',
            'components' => [
                $this->makeMenuComponent(
                    name: self::COMPONENT_GENRES,
                    options: RawgGenre::cases(),
                    defaults: $this->user->settings->genres,
                ),
                $this->makeButtonComponent(
                    label: 'Next',
                    name: self::COMPONENT_GENRES
                )
            ]
        ];
    }

    /**
     * @return array
     */
    private function handleGenres(array $values = []): array
    {
        return [
            'content' => 'Choose the period for game releases:',
            'components' => [
                $this->makeMenuComponent(
                    name: self::COMPONENT_PERIOD,
                    options: Period::cases(),
                    defaults: [$this->user->settings->period->value],
                    maxValues: 1
                ),
                $this->makeButtonComponent(
                    label: 'Next',
                    name: self::COMPONENT_PERIOD
                )
            ]
        ];
    }

    /**
     * @return array
     */
    private function handlePeriod(array $values = []): array
    {
        return [
            'content' => 'Choose how often you want notifications:',
            'components' => [
                $this->makeMenuComponent(
                    name: self::COMPONENT_FREQUENCY,
                    options: Frequency::cases(),
                    defaults: [$this->user->settings->frequency->value],
                    maxValues: 1
                ),
                $this->makeButtonComponent(
                    label: 'Next',
                    name: self::COMPONENT_FREQUENCY
                )
            ]
        ];
    }

    /**
     * @return array
     */
    private function handleFrequency(array $values = []): array
    {
        return [
            'content'    => 'Your preferences have been updated!',
            'components' => []
        ];
    }
}
