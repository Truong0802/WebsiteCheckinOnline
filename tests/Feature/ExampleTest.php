<?php

namespace Tests\Feature;

use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Kiểm tra luồng web (bảng accounts): trang chào, form login, chặn dashboard khi chưa đăng nhập,
 * đăng nhập bằng username/đăng xuất, chặn tài khoản bị khóa.
 */
class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_returns_ok(): void
    {
        $this->get(route('home'))->assertOk();
    }

    public function test_login_page_returns_ok(): void
    {
        $this->get(route('login'))->assertOk()->assertSee('name="username"', false);
    }

    public function test_dashboard_redirects_guest_to_login(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_account_can_login_and_logout_on_web(): void
    {
        $account = Account::factory()->create();

        $this->post(route('login'), ['username' => $account->username, 'password' => 'password'])
            ->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticatedAs($account, 'web');

        $this->get(route('dashboard'))->assertOk()->assertSee($account->username);

        $this->post(route('logout'))->assertRedirect('/');
        $this->assertGuest('web');
    }

    public function test_remember_me_stores_remember_token(): void
    {
        $account = Account::factory()->create(['remember_token' => null]);

        $this->post(route('login'), [
            'username' => $account->username,
            'password' => 'password',
            'remember' => 'on',
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertNotNull($account->fresh()->remember_token);
    }

    public function test_web_login_fails_with_wrong_password(): void
    {
        $account = Account::factory()->create();

        $this->post(route('login'), ['username' => $account->username, 'password' => 'wrong'])
            ->assertSessionHasErrors('username');
        $this->assertGuest('web');
    }

    public function test_inactive_account_cannot_login_on_web(): void
    {
        $account = Account::factory()->inactive()->create();

        $this->post(route('login'), ['username' => $account->username, 'password' => 'password'])
            ->assertSessionHasErrors('username');
        $this->assertGuest('web');
    }
}
