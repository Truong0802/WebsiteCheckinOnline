<?php

namespace App\Exceptions;

use Exception;

/**
 * Ném ra khi muốn API trả HTTP 204 (không có dữ liệu); không ghi log.
 */
class NoContentException extends Exception
{
    public function report(): bool
    {
        return false;
    }
}
