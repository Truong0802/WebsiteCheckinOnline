<?php

namespace App\Http\Resources\Supplier;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Dữ liệu supplier trả về client. Tọa độ là chuỗi decimal 8 chữ số (cast decimal:8) để không mất độ chính xác.
 *
 * @mixin Supplier
 */
class SupplierResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'base_location_x' => $this->base_location_x,
            'base_location_y' => $this->base_location_y,
            'status' => $this->status,
            'time_limit_num' => $this->time_limit_num,
            'type_access_id' => $this->type_access_id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
