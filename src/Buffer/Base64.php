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

namespace GryfOSS\DataTypes\Buffer;

use GryfOSS\DataTypes\DataTypes;

/**
 * Class Base64
 * @package GryfOSS\DataTypes\Buffer
 */
class Base64 extends AbstractBuffer
{
    /**
     * @param string|null $data
     * @return string
     */
    public function validatedDataTypeValue(?string $data): string
    {
        if (!DataTypes::isBase64($data)) {
            throw new \InvalidArgumentException('First argument must be a Base64 encoded string');
        }

        $decoded = base64_decode($data, strict: true);
        if ($decoded === false) {
            throw new \UnexpectedValueException('Base64 decode failed');
        }

        return $data;
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return [
            "data" => $this->encoded(),
            "len" => $this->len()
        ];
    }

    /**
     * @return string
     */
    public function encoded(): string
    {
        return $this->value();
    }

    /**
     * @return Binary
     */
    public function binary(): Binary
    {
        return new Binary(base64_decode($this->encoded()));
    }
}