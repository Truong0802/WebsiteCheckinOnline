<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Ngôn ngữ hỗ trợ (thư mục lang/{vi,en,ja}); client gửi qua header X-localization.
 */
final class LanguageEnum extends Enum
{
    const VIETNAMESE = 'vi';

    const ENGLISH = 'en';

    const JAPANESE = 'ja';
}
