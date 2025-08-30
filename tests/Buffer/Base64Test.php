<?php

declare(strict_types=1);

namespace GryfOSS\DataTypes\Tests\Buffer;

use GryfOSS\DataTypes\Buffer\Base64;
use GryfOSS\DataTypes\Buffer\Binary;
use PHPUnit\Framework\TestCase;

class Base64Test extends TestCase
{
    public function testConstructorValidBase64(): void
    {
        $buffer = new Base64("SGVsbG8gV29ybGQ="); // "Hello World" in base64
        $this->assertEquals("SGVsbG8gV29ybGQ=", $buffer->value());
    }

    public function testConstructorInvalidBase64(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('First argument must be a Base64 encoded string');
        new Base64("@#$%");
    }

    public function testConstructorDecodeFails(): void
    {
        // This might fail on some systems due to strict base64 validation
        $this->expectException(\InvalidArgumentException::class);
        new Base64("invalid base64 with spaces");
    }

    public function testDebugInfo(): void
    {
        $buffer = new Base64("SGVsbG8="); // "Hello" in base64
        $debug = $buffer->__debugInfo();
        $this->assertArrayHasKey('data', $debug);
        $this->assertArrayHasKey('len', $debug);
        $this->assertEquals("SGVsbG8=", $debug['data']);
        $this->assertEquals(8, $debug['len']);
    }

    public function testEncoded(): void
    {
        $buffer = new Base64("SGVsbG8=");
        $this->assertEquals("SGVsbG8=", $buffer->encoded());
    }

    public function testBinary(): void
    {
        $buffer = new Base64("SGVsbG8="); // "Hello" in base64
        $binary = $buffer->binary();
        $this->assertInstanceOf(Binary::class, $binary);
        $this->assertEquals("Hello", $binary->raw());
    }

    public function testValidBase64Patterns(): void
    {
        // Test various valid base64 patterns
        $validPatterns = [
            "QQ==",      // Single character
            "QWI=",      // Two characters
            "QWJj",      // Three characters
            "QWJjZA==",  // Four characters
            "MTIzNDU2Nzg5MA==", // Numbers
        ];

        foreach ($validPatterns as $pattern) {
            $buffer = new Base64($pattern);
            $this->assertEquals($pattern, $buffer->value());
        }
    }

    public function testSetMethod(): void
    {
        $buffer = new Base64("QQ==");
        $buffer->set("QWI=");
        $this->assertEquals("QWI=", $buffer->value());
    }

    public function testSetWithInvalidBase64(): void
    {
        $buffer = new Base64("QQ==");
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('First argument must be a Base64 encoded string');
        $buffer->set("invalid@#$");
    }
}
