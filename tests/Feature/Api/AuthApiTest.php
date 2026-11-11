<?php

namespace Tests\Feature\Api;

use App\Enums\AuthEnum;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Kiểm tra luồng API JWT trên bảng accounts:
 * register -> login (lấy JWT) -> profile -> refresh -> logout (blacklist), và format lỗi JSON.
 */
class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Đăng nhập API, trả access_token.
     */
    private function loginToken(Account $account): string
    {
        return $this->postJson('/api/auth/login', [
            'username' => $account->username,
            'password' => 'password',
            'account_type' => AuthEnum::ACCOUNT,
        ])->assertOk()->json('access_token');
    }

    public function test_register_creates_account(): void
    {
        $this->postJson('/api/auth/register', [
            'username' => 'tester',
            'email' => 'tester@example.com',
            'phone' => '0900000000',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'account_type' => AuthEnum::ACCOUNT,
        ])->assertCreated()
            ->assertJsonPath('data.username', 'tester')
            ->assertJsonPath('data.status', Account::STATUS_ACTIVE)
            ->assertJsonPath('data.account_type', AuthEnum::ACCOUNT_NAME)
            ->assertJsonMissingPath('data.password');

        $this->assertDatabaseHas('accounts', ['username' => 'tester', 'email' => 'tester@example.com']);
        $this->assertDatabaseMissing('users', ['email' => 'tester@example.com']);
    }

    public function test_register_without_email_is_allowed(): void
    {
        $this->postJson('/api/auth/register', [
            'username' => 'no-mail',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'account_type' => AuthEnum::ACCOUNT,
        ])->assertCreated()->assertJsonPath('data.email', null);
    }

    public function test_register_validation_error_returns_standard_json(): void
    {
        $this->postJson('/api/auth/register', [])
            ->assertStatus(422)
            ->assertJsonPath('status', false)
            ->assertJsonStructure(['status', 'message', 'errors' => ['username', 'password', 'account_type']]);
    }

    public function test_register_duplicate_username_returns_422(): void
    {
        $account = Account::factory()->create();

        $this->postJson('/api/auth/register', [
            'username' => $account->username,
            'password' => 'password123',
            'confirm_password' => 'password123',
            'account_type' => AuthEnum::ACCOUNT,
        ])->assertStatus(422)->assertJsonStructure(['errors' => ['username']]);
    }

    public function test_login_profile_logout_flow(): void
    {
        $account = Account::factory()->create();

        $response = $this->postJson('/api/auth/login', [
            'username' => $account->username,
            'password' => 'password',
            'account_type' => AuthEnum::ACCOUNT,
        ])->assertOk()
            ->assertJsonPath('token_type', 'Bearer')
            ->assertJsonStructure(['token_type', 'access_token', 'expired_at']);

        $token = $response->json('access_token');

        $this->withToken($token)->getJson('/api/auth/profile')
            ->assertOk()
            ->assertJsonPath('data.id', $account->id)
            ->assertJsonPath('data.username', $account->username);

        $this->withToken($token)->postJson('/api/auth/logout')->assertOk();

        // Token đã vào blacklist: gọi lại bị 401.
        // Trong test các request dùng chung container: reset guard + token JWT đã cache (singleton).
        $this->app['auth']->forgetGuards();
        $this->app['tymon.jwt']->unsetToken();
        $this->withToken($token)->getJson('/api/auth/profile')->assertUnauthorized();
    }

    public function test_refresh_returns_new_token_and_blacklists_old_one(): void
    {
        $account = Account::factory()->create();
        $oldToken = $this->loginToken($account);

        $newToken = $this->withToken($oldToken)->postJson('/api/auth/refresh')
            ->assertOk()
            ->assertJsonPath('token_type', 'Bearer')
            ->json('access_token');

        $this->assertNotSame($oldToken, $newToken);

        $this->app['auth']->forgetGuards();
        $this->app['tymon.jwt']->unsetToken();
        $this->withToken($oldToken)->getJson('/api/auth/profile')->assertUnauthorized();

        $this->app['auth']->forgetGuards();
        $this->app['tymon.jwt']->unsetToken();
        $this->withToken($newToken)->getJson('/api/auth/profile')->assertOk();
    }

    public function test_login_with_wrong_password_returns_401(): void
    {
        $account = Account::factory()->create();

        $this->postJson('/api/auth/login', [
            'username' => $account->username,
            'password' => 'wrong-password',
            'account_type' => AuthEnum::ACCOUNT,
        ])->assertUnauthorized()->assertJsonPath('status', false);
    }

    public function test_inactive_account_cannot_login(): void
    {
        $account = Account::factory()->inactive()->create();

        $this->postJson('/api/auth/login', [
            'username' => $account->username,
            'password' => 'password',
            'account_type' => AuthEnum::ACCOUNT,
        ])->assertUnauthorized()->assertJsonPath('status', false);
    }

    public function test_invalid_token_returns_401(): void
    {
        $this->withToken('not-a-jwt')->getJson('/api/auth/profile')
            ->assertUnauthorized()->assertJsonPath('status', false);
    }

    public function test_profile_without_token_returns_401(): void
    {
        $this->getJson('/api/auth/profile')->assertUnauthorized()->assertJsonPath('status', false);
    }

    public function test_localization_header_changes_message_language(): void
    {
        $this->withHeader('X-localization', 'vi')->getJson('/api/auth/profile')
            ->assertUnauthorized()
            ->assertJsonPath('message', __('message.errors.unauthorized', [], 'vi'));
    }

    public function test_unknown_api_route_returns_404_json(): void
    {
        $this->getJson('/api/not-exist')->assertNotFound()->assertJsonPath('status', false);
    }
}
