<?php

namespace Tests\Feature\Api;

use App\Contracts\Services\SupplierServiceInterface;
use App\Models\Account;
use App\Models\Supplier;
use App\Services\SupplierService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Kiểm tra CRUD /api/suppliers (Controller -> SupplierServiceInterface -> SupplierService -> bảng suppliers):
 * bắt buộc JWT, phân trang/filter/sort, validate, audit created_by/updated_by, 404 và chặn xóa khi đang bị FK tham chiếu.
 */
class SupplierApiTest extends TestCase
{
    use RefreshDatabase;

    private Account $account;

    protected function setUp(): void
    {
        parent::setUp();

        // Đăng nhập guard 'api' (JWT) bằng tài khoản test.
        $this->account = Account::factory()->create();
        $this->actingAs($this->account, 'api');
    }

    private function payload(array $override = []): array
    {
        return array_merge([
            'name' => 'Trường THPT A',
            'code' => 'SUP-001',
            'base_location_x' => 10.12345678,
            'base_location_y' => 106.87654321,
            'time_limit_num' => 15,
        ], $override);
    }

    public function test_interface_is_bound_to_service(): void
    {
        $this->assertInstanceOf(SupplierService::class, app(SupplierServiceInterface::class));
    }

    public function test_requires_authentication(): void
    {
        $this->app['auth']->forgetGuards();

        $this->getJson('/api/suppliers')->assertUnauthorized()->assertJsonPath('status', false);
        $this->postJson('/api/suppliers', $this->payload())->assertUnauthorized();
        $this->assertDatabaseCount('suppliers', 0);
    }

    public function test_index_returns_paginated_list_with_filter_and_sort(): void
    {
        Supplier::factory()->create(['name' => 'Bravo', 'code' => 'B-1']);
        Supplier::factory()->create(['name' => 'Alpha', 'code' => 'A-1']);
        Supplier::factory()->create(['name' => 'Zulu', 'code' => 'Z-1']);

        // filter[name]=a tìm gần đúng: khớp Alpha, Bravo; Zulu không chứa 'a' nên bị loại. sort=name tăng dần.
        $this->getJson('/api/suppliers?filter[name]=a&sort=name')
            ->assertOk()
            ->assertJsonStructure(['data' => [['id', 'name', 'code', 'status']], 'links', 'meta' => ['total', 'per_page']])
            ->assertJsonPath('meta.per_page', 20)
            ->assertJsonPath('meta.total', 2)
            ->assertJsonPath('data.0.name', 'Alpha')
            ->assertJsonPath('data.1.name', 'Bravo');

        // Không filter, sort=-name giảm dần: Zulu đứng đầu.
        $this->getJson('/api/suppliers?sort=-name')
            ->assertOk()->assertJsonPath('meta.total', 3)->assertJsonPath('data.0.name', 'Zulu');

        $this->getJson('/api/suppliers?filter[code]=B-1')
            ->assertOk()->assertJsonCount(1, 'data')->assertJsonPath('data.0.code', 'B-1');
    }

    public function test_index_rejects_filter_not_in_allow_list(): void
    {
        $this->getJson('/api/suppliers?filter[created_by]=1')->assertStatus(400);
    }

    public function test_store_creates_supplier_with_audit_fields(): void
    {
        // Không gửi status => mặc định 1; created_by client gửi bị bỏ qua.
        $this->postJson('/api/suppliers', $this->payload([
            'created_by' => 999,
        ]))->assertCreated()
            ->assertJsonPath('data.code', 'SUP-001')
            ->assertJsonPath('data.base_location_x', '10.12345678')
            ->assertJsonPath('data.base_location_y', '106.87654321')
            ->assertJsonPath('data.status', Supplier::STATUS_ACTIVE)
            ->assertJsonPath('data.created_by', $this->account->id)
            ->assertJsonPath('data.updated_by', $this->account->id);

        $this->assertDatabaseHas('suppliers', [
            'code' => 'SUP-001',
            'status' => Supplier::STATUS_ACTIVE,
            'created_by' => $this->account->id,
            'time_limit_num' => 15,
        ]);
    }

    public function test_store_accepts_inactive_status(): void
    {
        $this->postJson('/api/suppliers', $this->payload(['status' => Supplier::STATUS_INACTIVE]))
            ->assertCreated()->assertJsonPath('data.status', Supplier::STATUS_INACTIVE);

        $this->assertDatabaseHas('suppliers', ['code' => 'SUP-001', 'status' => Supplier::STATUS_INACTIVE]);
    }

    public function test_status_outside_0_1_returns_422(): void
    {
        $supplier = Supplier::factory()->create();

        $this->postJson('/api/suppliers', $this->payload(['status' => 2]))
            ->assertStatus(422)->assertJsonValidationErrors(['status']);
        $this->patchJson("/api/suppliers/{$supplier->id}", ['status' => 'abc'])
            ->assertStatus(422)->assertJsonValidationErrors(['status']);
        $this->patchJson("/api/suppliers/{$supplier->id}", ['status' => null])
            ->assertStatus(422)->assertJsonValidationErrors(['status']);

        $this->assertDatabaseHas('suppliers', ['id' => $supplier->id, 'status' => Supplier::STATUS_ACTIVE]);
    }

    public function test_patch_toggles_status(): void
    {
        $supplier = Supplier::factory()->create();

        $this->patchJson("/api/suppliers/{$supplier->id}", ['status' => Supplier::STATUS_INACTIVE])
            ->assertOk()->assertJsonPath('data.status', Supplier::STATUS_INACTIVE);

        // PATCH field khác không gửi status => giữ nguyên 0.
        $this->patchJson("/api/suppliers/{$supplier->id}", ['name' => 'Renamed'])
            ->assertOk()->assertJsonPath('data.status', Supplier::STATUS_INACTIVE);

        $this->patchJson("/api/suppliers/{$supplier->id}", ['status' => Supplier::STATUS_ACTIVE])
            ->assertOk()->assertJsonPath('data.status', Supplier::STATUS_ACTIVE);
    }

    public function test_store_with_existing_type_access(): void
    {
        $typeAccessId = DB::table('type_accesses')->insertGetId(['name' => 'GPS', 'type' => 1]);

        $this->postJson('/api/suppliers', $this->payload(['type_access_id' => $typeAccessId]))
            ->assertCreated()->assertJsonPath('data.type_access_id', $typeAccessId);
    }

    public function test_store_validation_errors(): void
    {
        $this->postJson('/api/suppliers', [])
            ->assertStatus(422)->assertJsonValidationErrors(['name', 'code']);

        $this->postJson('/api/suppliers', $this->payload([
            'code' => str_repeat('c', 51),
            'base_location_x' => 10.123456789,
            'base_location_y' => 1000,
            'time_limit_num' => -1,
            'type_access_id' => 999,
        ]))->assertStatus(422)->assertJsonValidationErrors([
            'code', 'base_location_x', 'base_location_y', 'time_limit_num', 'type_access_id',
        ]);

        $this->assertDatabaseCount('suppliers', 0);
    }

    public function test_store_duplicate_code_returns_422(): void
    {
        Supplier::factory()->create(['code' => 'SUP-001']);

        $this->postJson('/api/suppliers', $this->payload())
            ->assertStatus(422)->assertJsonValidationErrors(['code']);
    }

    public function test_show_returns_supplier_and_404_when_missing(): void
    {
        $supplier = Supplier::factory()->create();

        $this->getJson("/api/suppliers/{$supplier->id}")
            ->assertOk()->assertJsonPath('data.id', $supplier->id)->assertJsonPath('data.code', $supplier->code);

        $this->getJson('/api/suppliers/999999')->assertNotFound()->assertJsonPath('status', false);
    }

    public function test_patch_updates_only_sent_fields_and_updated_by(): void
    {
        $supplier = Supplier::factory()->create(['name' => 'Old', 'code' => 'KEEP-1', 'created_by' => 123]);

        $this->patchJson("/api/suppliers/{$supplier->id}", ['name' => 'New'])
            ->assertOk()
            ->assertJsonPath('data.name', 'New')
            ->assertJsonPath('data.code', 'KEEP-1')
            ->assertJsonPath('data.updated_by', $this->account->id);

        $this->assertDatabaseHas('suppliers', [
            'id' => $supplier->id,
            'name' => 'New',
            'code' => 'KEEP-1',
            'created_by' => 123,
            'updated_by' => $this->account->id,
        ]);
    }

    public function test_update_code_unique_ignores_itself_but_not_others(): void
    {
        $supplier = Supplier::factory()->create(['code' => 'SELF-1']);
        Supplier::factory()->create(['code' => 'OTHER-1']);

        $this->putJson("/api/suppliers/{$supplier->id}", $this->payload(['code' => 'SELF-1']))->assertOk();

        $this->putJson("/api/suppliers/{$supplier->id}", $this->payload(['code' => 'OTHER-1']))
            ->assertStatus(422)->assertJsonValidationErrors(['code']);
    }

    public function test_destroy_deletes_supplier(): void
    {
        $supplier = Supplier::factory()->create();

        $this->deleteJson("/api/suppliers/{$supplier->id}")
            ->assertOk()->assertJsonPath('message', __('message.supplier.deleted'));

        $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
        $this->deleteJson("/api/suppliers/{$supplier->id}")->assertNotFound();
    }

    public function test_destroy_referenced_supplier_returns_409_and_keeps_data(): void
    {
        $supplier = Supplier::factory()->create();
        DB::table('class_rooms')->insert(['name' => '10A1', 'supplier_id' => $supplier->id]);

        $this->deleteJson("/api/suppliers/{$supplier->id}")
            ->assertStatus(409)
            ->assertJsonPath('status', false)
            ->assertJsonPath('message', __('message.supplier.in_use'));

        $this->assertDatabaseHas('suppliers', ['id' => $supplier->id]);
    }
}
