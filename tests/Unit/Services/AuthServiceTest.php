<?php

namespace Tests\Unit\Services;

use App\Enums\Scope;
use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\InvalidScopeException;
use App\Services\AuthService;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use UnexpectedValueException;

class AuthServiceTest extends TestCase
{
    use DatabaseMigrations;

    private AuthService $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = resolve(AuthService::class);
        Config::set('app.key', $this->faker->password(8, 12));
    }

    public function test_should_generate_jwt()
    {
        $password = $this->faker->password(8, 12);
        $user = $this->createUser($password);

        $jwt = $this->service->generateJWT([
            'email'    => $user->email,
            'password' => $password
        ]);

        $this->assertArrayHasKey('token', $jwt);
        $this->assertArrayHasKey('expires_at', $jwt);
    }

    public function test_should_throw_invalid_credentials_exception()
    {
        $password = $this->faker->password(8, 12);
        $user = $this->createUser($password);

        $this->expectException(InvalidCredentialsException::class);

        $this->service->generateJWT([
            'email'    => $user->email,
            'password' => $this->faker->password(8, 12)
        ]);
    }

    public function test_should_decode_jwt()
    {
        $password = $this->faker->password(8, 12);
        $user = $this->createUser($password);

        $jwt = $this->service->generateJWT([
            'email'    => $user->email,
            'password' => $password
        ]);

        $decoded = $this->service->decodeJWT($jwt['token']);

        $this->assertEquals($user->id, $decoded->sub);
    }

    public function test_should_throw_invalid_signature_exception()
    {
        $token = JWT::encode([
            'iss' => env('APP_URL'),
            'sub' => $this->faker->randomNumber(1),
            'iat' => time(),
            'exp' => time() + 3600,
        ], $this->faker->password(8, 12), 'HS256');

        $this->expectException(SignatureInvalidException::class);

        $this->service->decodeJWT($token);
    }

    public function test_should_throw_unexpected_value_exception()
    {
        $this->expectException(UnexpectedValueException::class);

        $this->service->decodeJWT(
            $this->faker->password(8, 12)
        );
    }

    public function test_should_have_scope()
    {
        $user = $this->createUser();
        Auth::setUser($user);

        $this->assertTrue($this->service->checkScopes(Scope::Default->value));
    }

    public function test_should_throw_authentication_exception()
    {
        $this->expectException(AuthenticationException::class);

        $this->service->checkScopes(Scope::Default->value);
    }

    #[DataProvider('provider_scopes')]
    public function test_should_throw_invalid_scope_exception(array $scopes)
    {
        $user = $this->createUser();
        Auth::setUser($user);

        $this->expectException(InvalidScopeException::class);

        $this->assertFalse($this->service->checkScopes(...$scopes));
    }

    public static function provider_scopes(): array
    {
        return [
            [[Scope::Root->value]],
            [Scope::values()],
        ];
    }
}
