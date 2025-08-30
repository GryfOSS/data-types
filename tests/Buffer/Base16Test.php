<?php

declare(strict_types=1);

namespace GryfOSS\DataTypes\Tests\Buffer;

use GryfOSS\DataTypes\Buffer\Base16;
use GryfOSS\DataTypes\Buffer\Binary;
use GryfOSS\DataTypes\Buffer\Base16\Decoder;
use PHPUnit\Framework\TestCase;

class Base16Test extends TestCase
{
    public function testConstructorValidHex(): void
    {
        $buffer = new Base16("ff");
        $this->assertEquals("ff", $buffer->value());
    }

    public function testConstructorWithPrefix(): void
    {
        $buffer = new Base16("0xff");
        $this->assertEquals("ff", $buffer->value());
    }

    public function testConstructorUpperCase(): void
    {
        $buffer = new Base16("FF");
        $this->assertEquals("FF", $buffer->value());
    }

    public function testConstructorOddLength(): void
    {
        $buffer = new Base16("f");
        $this->assertEquals("0f", $buffer->value());
    }

    public function testConstructorEmpty(): void
    {
        $buffer = new Base16("");
        $this->assertEquals("", $buffer->value());
    }

    public function testConstructorInvalidHex(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('First argument must be a Hexadecimal value');
        new Base16("xyz");
    }

    public function testDebugInfo(): void
    {
        $buffer = new Base16("ff");
        $debug = $buffer->__debugInfo();
        $this->assertArrayHasKey('data', $debug);
        $this->assertArrayHasKey('len', $debug);
        $this->assertEquals("0xff", $debug['data']);
        $this->assertEquals(2, $debug['len']);
    }

    public function testHexitsWithoutPrefix(): void
    {
        $buffer = new Base16("ff");
        $this->assertEquals("ff", $buffer->hexits(false));
    }

    public function testHexitsWithPrefix(): void
    {
        $buffer = new Base16("ff");
        $this->assertEquals("0xff", $buffer->hexits(true));
    }

    public function testHexitsEmptyBuffer(): void
    {
        $buffer = new Base16("");
        $this->assertEquals("", $buffer->hexits(false));
        $this->assertEquals("", $buffer->hexits(true)); // Empty string without prefix
    }

    public function testBinary(): void
    {
        $buffer = new Base16("48656c6c6f"); // "Hello" in hex
        $binary = $buffer->binary();
        $this->assertInstanceOf(Binary::class, $binary);
        $this->assertEquals("Hello", $binary->raw());
    }

    public function testDecode(): void
    {
        $buffer = new Base16("ff");
        $decoder = $buffer->decode();
        $this->assertInstanceOf(Decoder::class, $decoder);

        // Test that multiple calls return same instance
        $decoder2 = $buffer->decode();
        $this->assertSame($decoder, $decoder2);
    }

    public function testValidationWithMixedCase(): void
    {
        $buffer = new Base16("DeAdBeEf");
        $this->assertEquals("DeAdBeEf", $buffer->value());
    }

    public function testValidationWithNumbers(): void
    {
        $buffer = new Base16("123456789abcdef");
        $this->assertEquals("0123456789abcdef", $buffer->value()); // Odd length gets padded
    }

    public function testSetMethod(): void
    {
        $buffer = new Base16("ff");
        $buffer->set("00");
        $this->assertEquals("00", $buffer->value());
    }

    public function testSetWithInvalidHex(): void
    {
        $buffer = new Base16("ff");
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('First argument must be a Hexadecimal value');
        $buffer->set("xyz");
    }

    public function testAppendValidHex(): void
    {
        $buffer = new Base16("ff");
        $buffer->append("00");
        $this->assertEquals("ff00", $buffer->value());
    }

    public function testAppendWithInvalidHex(): void
    {
        $buffer = new Base16("ff");
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('First argument must be a Hexadecimal value');
        $buffer->append("xyz");
    }

    public function testPrependValidHex(): void
    {
        $buffer = new Base16("ff");
        $buffer->prepend("00");
        $this->assertEquals("00ff", $buffer->value());
    }

    public function testCopy(): void
    {
        $buffer = new Base16("deadbeef");
        $copy = $buffer->copy(2, 4);
        $this->assertInstanceOf(Base16::class, $copy);
        $this->assertEquals("adbe", $copy->value());
    }
}
