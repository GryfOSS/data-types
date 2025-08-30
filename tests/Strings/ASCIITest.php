<?php

declare(strict_types=1);

namespace GryfOSS\DataTypes\Tests\Strings;

use GryfOSS\DataTypes\Buffer\Base16;
use GryfOSS\DataTypes\Strings\ASCII;
use PHPUnit\Framework\TestCase;

class ASCIITest extends TestCase
{
    public function testBase16Encode(): void
    {
        $result = ASCII::base16Encode("Hello");
        $this->assertInstanceOf(Base16::class, $result);
        $this->assertEquals("48656c6c6f", $result->hexits());
    }

    public function testBase16EncodeEmpty(): void
    {
        $result = ASCII::base16Encode("");
        $this->assertInstanceOf(Base16::class, $result);
        $this->assertEquals("", $result->hexits());
    }

    public function testBase16EncodeSpecialChars(): void
    {
        $result = ASCII::base16Encode("!@#$%");
        $this->assertInstanceOf(Base16::class, $result);
        $this->assertEquals("2140232425", $result->hexits()); // 5 characters: ! @ # $ %
    }

    public function testBase16EncodeNumbers(): void
    {
        $result = ASCII::base16Encode("12345");
        $this->assertInstanceOf(Base16::class, $result);
        $this->assertEquals("3132333435", $result->hexits());
    }

    public function testBase16EncodeWithUtf8(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot encode UTF-8 string into hexadecimals');
        ASCII::base16Encode("Héllo");
    }

    public function testBase16Decode(): void
    {
        $hex = new Base16("48656c6c6f");
        $result = ASCII::base16Decode($hex);
        $this->assertEquals("Hello", $result);
    }

    public function testBase16DecodeEmpty(): void
    {
        $hex = new Base16("");
        $result = ASCII::base16Decode($hex);
        $this->assertEquals("", $result);
    }

    public function testBase16DecodeSpecialChars(): void
    {
        $hex = new Base16("21402324");
        $result = ASCII::base16Decode($hex);
        $this->assertEquals("!@#$", $result);
    }

    public function testBase16DecodeNumbers(): void
    {
        $hex = new Base16("3132333435");
        $result = ASCII::base16Decode($hex);
        $this->assertEquals("12345", $result);
    }

    public function testBase16DecodeUpperCase(): void
    {
        $hex = new Base16("48656C6C6F");
        $result = ASCII::base16Decode($hex);
        $this->assertEquals("Hello", $result);
    }

    public function testBase16DecodeMixedCase(): void
    {
        $hex = new Base16("48656c6C6f");
        $result = ASCII::base16Decode($hex);
        $this->assertEquals("Hello", $result);
    }

    public function testRoundTripEncoding(): void
    {
        $original = "Hello, World! 123";
        $encoded = ASCII::base16Encode($original);
        $decoded = ASCII::base16Decode($encoded);
        $this->assertEquals($original, $decoded);
    }

    public function testBinaryData(): void
    {
        $binaryString = "\x00\x01\x02\x03\xFF";
        $encoded = ASCII::base16Encode($binaryString);
        $this->assertEquals("00010203ff", $encoded->hexits());

        $decoded = ASCII::base16Decode($encoded);
        $this->assertEquals($binaryString, $decoded);
    }

    public function testLargeString(): void
    {
        $largeString = str_repeat("A", 1000);
        $encoded = ASCII::base16Encode($largeString);
        $decoded = ASCII::base16Decode($encoded);
        $this->assertEquals($largeString, $decoded);
    }

    public function testAllAsciiCharacters(): void
    {
        // Test encoding/decoding of all printable ASCII characters
        $ascii = '';
        for ($i = 32; $i <= 126; $i++) {
            $ascii .= chr($i);
        }

        $encoded = ASCII::base16Encode($ascii);
        $decoded = ASCII::base16Decode($encoded);
        $this->assertEquals($ascii, $decoded);
    }
}
