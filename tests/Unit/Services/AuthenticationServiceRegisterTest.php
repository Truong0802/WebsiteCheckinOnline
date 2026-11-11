<?php

namespace Tests\Unit\Services;

use App\Enums\AuthEnum;
use App\Models\Account;
use App\Services\AuthenticationService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Unit test cho AuthenticationService::register() (không đi qua HTTP/FormRequest):
 * - account_type hợp lệ => tạo bản ghi ở bảng accounts qua model Account.
 * - Chỉ field trong $fillable được ghi; status/created_by bị bỏ qua và status lấy mặc định.
 * - Password lưu dạng hash, không lưu plain text.
 * - account_type không có trong map multiAuth => AuthenticationException, không ghi DB.
 * Dùng SQLite in-memory theo phpunit.xml, RefreshDatabase tự dọn dữ liệu sau mỗi test.
 */
class AuthenticationServiceRegisterTest extends TestCase
{
    use RefreshDatabase;

    private AuthenticationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        // Cấu hình service giống AuthController::__construct().
        $this->service = new AuthenticationService;
        $this->service->setDefaultModel(Account::class);
        $this->service->setMultiAuth([AuthEnum::ACCOUNT => Account::class]);
    }

    public function test_register_stores_account_in_accounts_table(): void
    {
        $account = $this->service->register([
            'username' => 'unit-user',
            'email' => 'unit@example.com',
            'phone' => '0911111111',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'account_type' => AuthEnum::ACCOUNT,
        ]);

        $this->assertInstanceOf(Account::class, $account);
        $this->assertSame('accounts', $account->getTable());
        $this->assertTrue($account->wasRecentlyCreated);
        $this->assertSame(Account::STATUS_ACTIVE, $account->status);
        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'username' => 'unit-user',
            'email' => 'unit@example.com',
            'phone' => '0911111111',
            'status' => Account::STATUS_ACTIVE,
        ]);
    }

    public function test_register_hashes_password(): void
    {
        $account = $this->service->register([
            'username' => 'hash-user',
            'password' => 'password123',
            'account_type' => AuthEnum::ACCOUNT,
        ]);

        $stored = Account::query()->whereKey($account->id)->value('password');

        $this->assertNotSame('password123', $stored);
        $this->assertTrue(Hash::check('password123', $stored));
    }

    public function test_register_ignores_non_fillable_fields(): void
    {
        // Client cố gửi status/created_by: phải bị $fillable loại bỏ.
        $account = $this->service->register([
            'username' => 'mass-assign',
            'password' => 'password123',
            'account_type' => AuthEnum::ACCOUNT,
            'status' => 0,
            'created_by' => 999,
        ]);

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'status' => Account::STATUS_ACTIVE,
            'created_by' => null,
        ]);
    }

    public function test_register_with_unknown_account_type_throws_and_does_not_insert(): void
    {
        try {
            $this->service->register([
                'username' => 'bad-type',
                'password' => 'password123',
                'account_type' => 999,
            ]);
            $this->fail('Expected AuthenticationException was not thrown.');
        } catch (AuthenticationException) {
            $this->assertDatabaseMissing('accounts', ['username' => 'bad-type']);
        }
    }

    public function test_register_without_account_type_throws(): void
    {
        $this->expectException(AuthenticationException::class);

        $this->service->register([
            'username' => 'no-type',
            'password' => 'password123',
        ]);
    }
}
