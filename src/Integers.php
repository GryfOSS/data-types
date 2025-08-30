<?php
/**
 * This file is a part of "GryfOSS/data-types" package.
 * https://github.com/GryfOSS/data-types
 *
 * Copyright (c) Furqan A. Siddiqui <hello@furqansiddiqui.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code or visit following link:
 * https://github.com/GryfOSS/data-types/blob/master/LICENSE
 */

declare(strict_types=1);

namespace GryfOSS\DataTypes;

/**
 * Class Integers
 * @package GryfOSS\DataTypes
 */
class Integers
{
    /**
     * @param int $num
     * @param int $from
     * @param int $to
     * @return bool
     */
    public static function Range(int $num, int $from, int $to): bool
    {
        return ($num >= $from && $num <= $to);
    }
}
