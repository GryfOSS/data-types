<?php

declare(strict_types=1);

namespace GryfOSS\DataTypes\Tests\Buffer\Binary;

use GryfOSS\DataTypes\Buffer\Binary;
use GryfOSS\DataTypes\Buffer\Binary\LenSize;
use PHPUnit\Framework\TestCase;

class LenSizeTest extends TestCase
{
    public function testConstructor(): void
    {
        $binary = new Binary("Hello");
        $lenSize = new LenSize($binary);
        $this->assertInstanceOf(LenSize::class, $lenSize);
    }

    public function testLen(): void
    {
        $binary = new Binary("Hello");
        $lenSize = new LenSize($binary);
        $this->assertEquals(5, $lenSize->len());
    }

    public function testBytes(): void
    {
        $binary = new Binary("Hello");
        $lenSize = new LenSize($binary);
        $this->assertEquals(5, $lenSize->bytes());
    }

    public function testBits(): void
    {
        $binary = new Binary("Hello");
        $lenSize = new LenSize($binary);
        $this->assertEquals(40, $lenSize->bits()); // 5 bytes * 8 bits
    }

    public function testWithEmptyBinary(): void
    {
        $binary = new Binary("");
        $lenSize = new LenSize($binary);
        $this->assertEquals(0, $lenSize->len());
        $this->assertEquals(0, $lenSize->bytes());
        $this->assertEquals(0, $lenSize->bits());
    }

    public function testWithUnicodeBinary(): void
    {
        $binary = new Binary("Héllo"); // "é" takes 2 bytes in UTF-8
        $lenSize = new LenSize($binary);
        $this->assertEquals(5, $lenSize->len());    // 5 characters
        $this->assertEquals(6, $lenSize->bytes());  // 6 bytes (é = 2 bytes)
        $this->assertEquals(48, $lenSize->bits());  // 6 bytes * 8 bits
    }

    public function testWithBinaryData(): void
    {
        $binaryData = "\x00\x01\x02\x03\xFF";
        $binary = new Binary($binaryData);
        $lenSize = new LenSize($binary);
        $this->assertEquals(5, $lenSize->len());
        $this->assertEquals(5, $lenSize->bytes());
        $this->assertEquals(40, $lenSize->bits());
    }
}
