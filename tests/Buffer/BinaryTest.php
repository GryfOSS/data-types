<?php

declare(strict_types=1);

namespace GryfOSS\DataTypes\Tests\Buffer;

use GryfOSS\DataTypes\Buffer\Base16;
use GryfOSS\DataTypes\Buffer\Base64;
use GryfOSS\DataTypes\Buffer\Binary;
use GryfOSS\DataTypes\Buffer\Bitwise;
use GryfOSS\DataTypes\Buffer\Binary\ByteReader;
use GryfOSS\DataTypes\Buffer\Binary\Digest;
use GryfOSS\DataTypes\Buffer\Binary\LenSize;
use PHPUnit\Framework\TestCase;

class BinaryTest extends TestCase
{
    public function testConstructorWithString(): void
    {
        $buffer = new Binary("Hello");
        $this->assertEquals("Hello", $buffer->value());
        $this->assertEquals("Hello", $buffer->raw());
    }

    public function testConstructorWithNull(): void
    {
        $buffer = new Binary(null);
        $this->assertEquals("", $buffer->value());
        $this->assertEquals("", $buffer->raw());
    }

    public function testDebugInfo(): void
    {
        $buffer = new Binary("Hello");
        $debug = $buffer->__debugInfo();
        $this->assertArrayHasKey('data', $debug);
        $this->assertArrayHasKey('size', $debug);
        $this->assertArrayHasKey('bits', $debug);
        $this->assertEquals("0x48656c6c6f", $debug['data']); // "Hello" in hex
        $this->assertEquals(5, $debug['size']);
        $this->assertEquals(40, $debug['bits']);
    }

    public function testSize(): void
    {
        $buffer = new Binary("Hello");
        $size = $buffer->size();
        $this->assertInstanceOf(LenSize::class, $size);

        // Test that multiple calls return same instance
        $size2 = $buffer->size();
        $this->assertSame($size, $size2);
    }

    public function testBase16(): void
    {
        $buffer = new Binary("Hello");
        $base16 = $buffer->base16();
        $this->assertInstanceOf(Base16::class, $base16);
        $this->assertEquals("48656c6c6f", $base16->hexits());
    }

    public function testBase64(): void
    {
        $buffer = new Binary("Hello");
        $base64 = $buffer->base64();
        $this->assertInstanceOf(Base64::class, $base64);
        $this->assertEquals("SGVsbG8=", $base64->encoded());
    }

    public function testBitwise(): void
    {
        $buffer = new Binary("A"); // ASCII 65 = 01000001 in binary
        $bitwise = $buffer->bitwise();
        $this->assertInstanceOf(Bitwise::class, $bitwise);
        $this->assertEquals("01000001", $bitwise->value());
    }

    public function testHash(): void
    {
        $buffer = new Binary("Hello");
        $digest = $buffer->hash();
        $this->assertInstanceOf(Digest::class, $digest);
    }

    public function testRead(): void
    {
        $buffer = new Binary("Hello");
        $reader = $buffer->read();
        $this->assertInstanceOf(ByteReader::class, $reader);
    }

    public function testValidatedDataTypeValue(): void
    {
        $buffer = new Binary();
        $reflection = new \ReflectionClass($buffer);
        $method = $reflection->getMethod('validatedDataTypeValue');
        $method->setAccessible(true);

        $this->assertEquals("test", $method->invoke($buffer, "test"));
        $this->assertEquals("", $method->invoke($buffer, null));
        $this->assertEquals("", $method->invoke($buffer, ""));
    }

    public function testBinaryWithUnicodeString(): void
    {
        $buffer = new Binary("Héllo");
        $this->assertEquals("Héllo", $buffer->raw());
        $this->assertEquals(6, $buffer->size()->bytes()); // UTF-8 "é" takes 2 bytes
    }

    public function testBinaryWithBinaryData(): void
    {
        $binaryData = "\x00\x01\x02\x03\xFF";
        $buffer = new Binary($binaryData);
        $this->assertEquals($binaryData, $buffer->raw());
        $this->assertEquals(5, $buffer->size()->bytes());
    }

    public function testSetMethod(): void
    {
        $buffer = new Binary("Hello");
        $buffer->set("World");
        $this->assertEquals("World", $buffer->raw());
    }

    public function testAppendBinary(): void
    {
        $buffer = new Binary("Hello");
        $buffer->append(" World");
        $this->assertEquals("Hello World", $buffer->raw());
    }

    public function testCopy(): void
    {
        $buffer = new Binary("Hello World");
        $copy = $buffer->copy(6, 5); // Extract "World"
        $this->assertInstanceOf(Binary::class, $copy);
        $this->assertEquals("World", $copy->raw());

        // Original should be unchanged
        $this->assertEquals("Hello World", $buffer->raw());
    }
}
