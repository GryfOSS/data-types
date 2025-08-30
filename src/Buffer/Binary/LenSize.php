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

namespace GryfOSS\DataTypes\Buffer\Binary;

use GryfOSS\DataTypes\Buffer\Binary;

/**
 * Class LenSize
 * @package GryfOSS\DataTypes\Buffer\Binary
 */
class LenSize
{
    /** @var Binary */
    private $buffer;

    /**
     * LenSize constructor.
     * @param Binary $binary
     */
    public function __construct(Binary $binary)
    {
        $this->buffer = $binary;
    }

    /**
     * @return int
     */
    public function len(): int
    {
        return $this->buffer->length;
    }

    /**
     * @return int
     */
    public function bytes(): int
    {
        return $this->buffer->sizeInBytes;
    }

    /**
     * @return int
     */
    public function bits(): int
    {
        return $this->buffer->sizeInBytes * 8;
    }
}