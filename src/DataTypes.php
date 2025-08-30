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

use GryfOSS\DataTypes\BcMath\BcMath;

/**
 * Class DataTypes
 * @package GryfOSS\DataTypes
 */
class DataTypes
{
    /** string Version (Major.Minor.Release-Suffix) */
    public const VERSION = "1.0.34";
    /** int Version (Major * 10000 + Minor * 100 + Release) */
    public const VERSION_ID = 10034;

    /**
     * Checks is string is comprised of only 1s and 0s
     * @param $val
     * @return bool
     */
    public static function isBitwise($val): bool
    {
        return is_string($val) && preg_match('/^[01]+$/', $val);
    }

    /**
     * Checks if argument is of type String and encoded in Base16
     * @param $val
     * @return bool
     */
    public static function isBase16($val): bool
    {
        return is_string($val) && preg_match('/^(0x)?[a-f0-9]+$/i', $val);
    }

    /**
     * Checks if argument is of type String and encoded as Hexadecimals (Base16)
     * @param $val
     * @return bool
     */
    public static function isHex($val): bool
    {
        return self::isBase16($val);
    }

    /**
     * Checks if argument is of type String and encoded in Base64
     * @param $val
     * @return bool
     */
    public static function isBase64($val): bool
    {
        return is_string($val) && preg_match('/^[a-z0-9+\/]+={0,2}$/i', $val);
    }

    /**
     * Checks if string may have UTF8 characters
     * @param $val
     * @return bool
     */
    public static function isUtf8($val): bool
    {
        if (!is_string($val)) {
            return false;
        }

        return strlen($val) !== mb_strlen($val);
    }

    /**
     * @param $val
     * @return bool
     */
    public static function isNumeric($val): bool
    {
        return BcMath::isNumeric($val) ? true : false;
    }
}
