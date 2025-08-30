<?php

declare(strict_types=1);

namespace GryfOSS\DataTypes\Tests\Buffer\Base16;

use GryfOSS\DataTypes\BcNumber;
use GryfOSS\DataTypes\Buffer\AbstractBuffer;
use GryfOSS\DataTypes\Buffer\Base16;
use GryfOSS\DataTypes\Buffer\Base16\Decoder;
use GryfOSS\DataTypes\Buffer\Binary;
use GryfOSS\DataTypes\Buffer\Bitwise;
use PHPUnit\Framework\TestCase;

class DecoderTest extends TestCase
{
    public function testConstructor(): void
    {
        $base16 = new Base16("ff");
        $decoder = new Decoder($base16);
        $this->assertInstanceOf(Decoder::class, $decoder);
    }

    public function testBase10(): void
    {
        $base16 = new Base16("ff");
        $decoder = new Decoder($base16);
        $result = $decoder->base10();

        $this->assertInstanceOf(BcNumber::class, $result);
        $this->assertEquals("255", $result->value());
    }

    public function testInt(): void
    {
        $base16 = new Base16("ff");
        $decoder = new Decoder($base16);
        $result = $decoder->int();

        $this->assertInstanceOf(BcNumber::class, $result);
        $this->assertEquals("255", $result->value());
    }

    public function testAscii(): void
    {
        $base16 = new Base16("48656c6c6f"); // "Hello" in hex
        $decoder = new Decoder($base16);
        $result = $decoder->ascii();

        $this->assertEquals("Hello", $result);
    }

    public function testBinary(): void
    {
        $base16 = new Base16("48656c6c6f"); // "Hello" in hex
        $decoder = new Decoder($base16);
        $result = $decoder->binary();

        $this->assertInstanceOf(Binary::class, $result);
        $this->assertEquals("Hello", $result->raw());
    }

    public function testBitwise(): void
    {
        $base16 = new Base16("ff");
        $decoder = new Decoder($base16);
        $result = $decoder->bitwise();

        $this->assertInstanceOf(Bitwise::class, $result);
        $this->assertEquals("11111111", $result->value());
    }

    public function testBitwiseWithOddLength(): void
    {
        $base16 = new Base16("f"); // Should be padded to "0f"
        $decoder = new Decoder($base16);
        $result = $decoder->bitwise();

        $this->assertInstanceOf(Bitwise::class, $result);
        $this->assertEquals("00001111", $result->value());
    }

    public function testBitwiseWithLeadingZeros(): void
    {
        $base16 = new Base16("0f");
        $decoder = new Decoder($base16);
        $result = $decoder->bitwise();

        $this->assertInstanceOf(Bitwise::class, $result);
        $this->assertEquals("00001111", $result->value());
    }

    public function testBitwiseWithEmptyBuffer(): void
    {
        $base16 = new Base16("");
        $decoder = new Decoder($base16);

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Base16 buffer is NULL or empty');
        $decoder->bitwise();
    }

    public function testComplexHexDecoding(): void
    {
        $base16 = new Base16("deadbeef");
        $decoder = new Decoder($base16);

        $number = $decoder->base10();
        $this->assertEquals("3735928559", $number->value());

        $bitwise = $decoder->bitwise();
        $this->assertEquals("11011110101011011011111011101111", $bitwise->value());
    }

    public function testZeroValue(): void
    {
        $base16 = new Base16("00");
        $decoder = new Decoder($base16);

        $number = $decoder->base10();
        $this->assertEquals("0", $number->value());

        $bitwise = $decoder->bitwise();
        $this->assertEquals("00000000", $bitwise->value());
    }

    public function testSingleDigit(): void
    {
        $base16 = new Base16("a");
        $decoder = new Decoder($base16);

        $number = $decoder->base10();
        $this->assertEquals("10", $number->value());

        $bitwise = $decoder->bitwise();
        $this->assertEquals("00001010", $bitwise->value());
    }

    public function testBitwiseWithNullBuffer(): void
    {
        // Create a buffer and force it to be empty to test the exception
        $buffer = new Base16("00");
        $decoder = new Decoder($buffer);

        // Use reflection to force the buffer data to be empty
        $reflection = new \ReflectionClass(AbstractBuffer::class);
        $property = $reflection->getProperty('data');
        $property->setAccessible(true);
        $property->setValue($buffer, '');

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Base16 buffer is NULL or empty');
        $decoder->bitwise();
    }

    public function testBitwiseWithOddNumberOfSymbols(): void
    {
        // Create a Base16 buffer and manually set it to have an odd number of symbols
        $buffer = new Base16("ab");
        $decoder = new Decoder($buffer);

        // Use reflection to force the buffer data to have odd number of symbols
        $reflection = new \ReflectionClass(AbstractBuffer::class);
        $property = $reflection->getProperty('data');
        $property->setAccessible(true);
        $property->setValue($buffer, 'abc'); // 3 symbols (odd)

        $bitwise = $decoder->bitwise();

        // Should pad with leading zero: 'abc' becomes '0abc'
        // 0abc in hex = 2748 in decimal = 001010111100 in binary (padded to 16 bits)
        $this->assertEquals("0000101010111100", $bitwise->value());
    }
}
