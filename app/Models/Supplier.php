<?php

namespace App\Models;

use Database\Factories\SupplierFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Nhà cung cấp/đơn vị (bảng suppliers): tọa độ gốc đối chiếu vị trí checkin,
 * giới hạn thời gian (time_limit_num) và loại quyền truy cập (type_access_id -> type_accesses).
 * Được class_rooms, supplier_configs, supplier_config_accounts, checkin_generates tham chiếu (FK).
 */
class Supplier extends Model
{
    /** @use HasFactory<SupplierFactory> */
    use HasFactory;

    /** Trạng thái hoạt động, trùng giá trị default của cột suppliers.status. */
    public const STATUS_ACTIVE = 1;

    /** Trạng thái ngừng hoạt động. */
    public const STATUS_INACTIVE = 0;

    /**
     * Field cho phép gán hàng loạt từ request.
     * status chỉ nhận 0/1 (validate ở FormRequest), không gửi thì mặc định STATUS_ACTIVE;
     * created_by/updated_by do service gán theo tài khoản đăng nhập.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'code',
        'base_location_x',
        'base_location_y',
        'status',
        'time_limit_num',
        'type_access_id',
    ];

    /**
     * Giá trị mặc định khi tạo mới, khớp default của DB để model trả về có sẵn status.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => self::STATUS_ACTIVE,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'base_location_x' => 'decimal:8',
            'base_location_y' => 'decimal:8',
            'status' => 'integer',
            'time_limit_num' => 'integer',
            'type_access_id' => 'integer',
            'created_by' => 'integer',
            'updated_by' => 'integer',
        ];
    }
}
