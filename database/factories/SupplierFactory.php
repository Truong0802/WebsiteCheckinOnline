<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Dữ liệu mẫu cho suppliers (dev/test); type_access_id để null vì bảng type_accesses chưa có model/factory.
 *
 * @extends Factory<Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'code' => fake()->unique()->bothify('SUP-####??'),
            'base_location_x' => fake()->latitude(),
            'base_location_y' => fake()->longitude(),
            'status' => Supplier::STATUS_ACTIVE,
            'time_limit_num' => fake()->numberBetween(5, 60),
            'type_access_id' => null,
        ];
    }
}
