<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Dữ liệu mẫu cho accounts; mật khẩu mặc định 'password' (chỉ dùng dev/test).
 *
 * @extends Factory<Account>
 */
class AccountFactory extends Factory
{
    protected static ?string $password;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->unique()->userName(),
            'phone' => fake()->numerify('09########'),
            'email' => fake()->unique()->safeEmail(),
            'status' => Account::STATUS_ACTIVE,
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Tài khoản bị khóa (status khác STATUS_ACTIVE) để test chặn đăng nhập.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 0,
        ]);
    }
}
