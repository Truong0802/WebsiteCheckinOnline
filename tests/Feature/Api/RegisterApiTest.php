<?php

namespace Tests\Feature\Api;

use App\Enums\AuthEnum;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Feature test bổ sung cho POST /api/auth/register (các case chưa có trong AuthApiTest):
 * validate từng field (RegisterRequest), chống mass assignment, hash password,
 * đăng ký xong đăng nhập được, và giới hạn throttle:10,1.
 */
class RegisterApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Payload hợp lệ mặc định; ghi đè từng field theo case.
     */
    private function payload(array $override = []): array
    {
        return array_merge([
            'username' => 'new-user',
            'email' => 'new-user@example.com',
            'phone' => '0900000001',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'account_type' => AuthEnum::ACCOUNT,
        ], $override);
    }

    public function test_register_returns_201_with_expected_structure(): void
    {
        $this->postJson('/api/auth/register', $this->payload())
            ->assertCreated()
            ->assertJsonStructure(['data' => [
                'id', 'account_type', 'username', 'email', 'phone', 'status', 'created_at', 'updated_at',
            ]])
            ->assertJsonMissingPath('data.password')
            ->assertJsonMissingPath('data.remember_token')
            ->assertJsonMissingPath('data.confirm_password');

        $this->assertDatabaseCount('accounts', 1);
    }

    public function test_register_stores_hashed_password_and_can_login(): void
    {
        $this->postJson('/api/auth/register', $this->payload())->assertCreated();

        $stored = Account::query()->where('username', 'new-user')->value('password');
        $this->assertNotSame('password123', $stored);
        $this->assertTrue(Hash::check('password123', $stored));

        $this->postJson('/api/auth/login', [
            'username' => 'new-user',
            'password' => 'password123',
            'account_type' => AuthEnum::ACCOUNT,
        ])->assertOk()->assertJsonPath('token_type', 'Bearer');
    }

    public function test_register_ignores_status_and_audit_fields_from_client(): void
    {
        $this->postJson('/api/auth/register', $this->payload([
            'status' => 0,
            'created_by' => 999,
            'updated_by' => 999,
        ]))->assertCreated()->assertJsonPath('data.status', Account::STATUS_ACTIVE);

        $this->assertDatabaseHas('accounts', [
            'username' => 'new-user',
            'status' => Account::STATUS_ACTIVE,
            'created_by' => null,
            'updated_by' => null,
        ]);
    }

    public function test_register_password_shorter_than_8_returns_422(): void
    {
        $this->postJson('/api/auth/register', $this->payload([
            'password' => 'short7c',
            'confirm_password' => 'short7c',
        ]))->assertStatus(422)->assertJsonValidationErrors(['password']);

        $this->assertDatabaseCount('accounts', 0);
    }

    public function test_register_confirm_password_mismatch_returns_422(): void
    {
        $this->postJson('/api/auth/register', $this->payload(['confirm_password' => 'different123']))
            ->assertStatus(422)->assertJsonValidationErrors(['confirm_password']);

        $this->assertDatabaseCount('accounts', 0);
    }

    public function test_register_invalid_account_type_returns_422(): void
    {
        $this->postJson('/api/auth/register', $this->payload(['account_type' => 999]))
            ->assertStatus(422)->assertJsonValidationErrors(['account_type']);

        $this->assertDatabaseCount('accounts', 0);
    }

    public function test_register_invalid_email_format_returns_422(): void
    {
        $this->postJson('/api/auth/register', $this->payload(['email' => 'not-an-email']))
            ->assertStatus(422)->assertJsonValidationErrors(['email']);
    }

    public function test_register_duplicate_email_returns_422(): void
    {
        Account::factory()->create(['email' => 'taken@example.com']);

        $this->postJson('/api/auth/register', $this->payload(['email' => 'taken@example.com']))
            ->assertStatus(422)->assertJsonValidationErrors(['email']);

        $this->assertDatabaseCount('accounts', 1);
    }

    public function test_register_field_max_length_returns_422(): void
    {
        $this->postJson('/api/auth/register', $this->payload([
            'username' => str_repeat('u', 101),
            'phone' => str_repeat('9', 21),
        ]))->assertStatus(422)->assertJsonValidationErrors(['username', 'phone']);
    }

    public function test_register_field_at_max_length_is_allowed(): void
    {
        $this->postJson('/api/auth/register', $this->payload([
            'username' => str_repeat('u', 100),
            'phone' => str_repeat('9', 20),
        ]))->assertCreated();
    }

    public function test_register_is_throttled_after_10_requests_per_minute(): void
    {
        // 10 request đầu (kể cả lỗi validate) vẫn được xử lý; request thứ 11 bị chặn 429.
        for ($i = 0; $i < 10; $i++) {
            $this->postJson('/api/auth/register', [])->assertStatus(422);
        }

        $this->postJson('/api/auth/register', $this->payload())
            ->assertStatus(429)
            ->assertJsonPath('status', false);

        $this->assertDatabaseCount('accounts', 0);
    }
}
