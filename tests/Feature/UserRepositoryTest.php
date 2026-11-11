<?php

namespace Tests\Feature;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Kiểm tra RepositoryServiceProvider tự bind interface và getWithPagination() đọc ?filter/?sort.
 */
class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_interface_is_bound_to_repository(): void
    {
        $this->assertInstanceOf(UserRepository::class, app(UserRepositoryInterface::class));
    }

    public function test_pagination_applies_filter_and_sort(): void
    {
        User::factory()->create(['name' => 'Bravo']);
        User::factory()->create(['name' => 'Alpha']);
        User::factory()->create(['name' => 'Charlie']);

        $this->app['request']->query->replace(['filter' => ['name' => 'a'], 'sort' => 'name']);

        $page = app(UserRepositoryInterface::class)->getWithPagination();

        // filter partial 'a' khớp Alpha, Bravo, Charlie; sort tăng dần theo name.
        $this->assertSame(['Alpha', 'Bravo', 'Charlie'], $page->pluck('name')->all());
    }
}
