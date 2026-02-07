<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class OrderStatus extends Enum
{
    const PENDING = 'Pending';
    const COMPLETED = 'Completed';
    const CONFIRMED = 'Confirmed';
    const CANCELLED = 'Cancelled';
}
