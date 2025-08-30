<?php

declare(strict_types=1);

namespace GryfOSS\DataTypes\Tests\Buffer;

use GryfOSS\DataTypes\Buffer\Base16;
use GryfOSS\DataTypes\Buffer\Binary;
use GryfOSS\DataTypes\Buffer\Bitwise;
use PHPUnit\Framework\TestCase;

class BitwiseTest extends TestCase
{
    public function testConstructorValidBitwise(): void
    {
        $buffer = new Bitwise("101010");
        $this->assertEquals("101010", $buffer->value());
    }

    public function testConstructorInvalidBitwise(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('First argument must be a Binary bitwise (1s and 0s) value');
        new Bitwise("102");
    }

    public function testConstructorWithNonBinaryChars(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('First argument must be a Binary bitwise (1s and 0s) value');
        new Bitwise("abc");
    }

    public function testDebugInfo(): void
    {
        $buffer = new Bitwise("101010");
        $debug = $buffer->__debugInfo();
        $this->assertArrayHasKey('data', $debug);
        $this->assertArrayHasKey('len', $debug);
        $this->assertEquals("101010", $debug['data']);
        $this->assertEquals(6, $debug['len']);
    }

    public function testBase16(): void
    {
        $buffer = new Bitwise("11111111"); // 255 in binary
        $base16 = $buffer->base16();
        $this->assertInstanceOf(Base16::class, $base16);
        $this->assertEquals("ff", $base16->hexits());
    }

    public function testBinary(): void
    {
        $buffer = new Bitwise("0100100001100101011011000110110001101111"); // "Hello" in binary
        $binary = $buffer->binary();
        $this->assertInstanceOf(Binary::class, $binary);
        $this->assertEquals("Hello", $binary->raw());
    }

    public function testBytes(): void
    {
        $buffer = new Bitwise("0100100001100101");
        $bytes = $buffer->bytes();
        $this->assertIsArray($bytes);
        // Remove empty elements that might be caused by chunk_split
        $bytes = array_filter($bytes, function($byte) { return $byte !== ''; });
        $bytes = array_values($bytes); // Re-index
        $this->assertEquals(["01001000", "01100101"], $bytes);
    }

    public function testChunks(): void
    {
        $buffer = new Bitwise("101010111100");
        $chunks = $buffer->chunks(4);
        $this->assertIsArray($chunks);
        // Remove empty elements that might be caused by chunk_split
        $chunks = array_filter($chunks, function($chunk) { return $chunk !== ''; });
        $chunks = array_values($chunks); // Re-index
        $this->assertEquals(["1010", "1011", "1100"], $chunks);
    }

    public function testChunksWithOddLength(): void
    {
        $buffer = new Bitwise("10101011110");
        $chunks = $buffer->chunks(4);
        $this->assertIsArray($chunks);
        // Remove empty elements that might be caused by chunk_split
        $chunks = array_filter($chunks, function($chunk) { return $chunk !== ''; });
        $chunks = array_values($chunks); // Re-index
        $this->assertEquals(["1010", "1011", "110"], $chunks);
    }

    public function testValidBitwisePatterns(): void
    {
        $validPatterns = [
            "1",
            "01",
            "10",
            "101010",
            "11111111",
            "00000000",
            "0110101110101",
        ];

        foreach ($validPatterns as $pattern) {
            $buffer = new Bitwise($pattern);
            $this->assertEquals($pattern, $buffer->value());
        }

        // Test zero separately
        $buffer = new Bitwise("0");
        $this->assertEquals("0", $buffer->value());
    }

    public function testSetMethod(): void
    {
        $buffer = new Bitwise("101");
        $buffer->set("010");
        $this->assertEquals("010", $buffer->value());
    }

    public function testSetWithInvalidBitwise(): void
    {
        $buffer = new Bitwise("101");
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('First argument must be a Binary bitwise (1s and 0s) value');
        $buffer->set("102");
    }

    public function testAppendValidBitwise(): void
    {
        $buffer = new Bitwise("101");
        $buffer->append("010");
        $this->assertEquals("101010", $buffer->value());
    }

    public function testAppendInvalidBitwise(): void
    {
        $buffer = new Bitwise("101");
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('First argument must be a Binary bitwise (1s and 0s) value');
        $buffer->append("102");
    }
}
