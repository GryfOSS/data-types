<?php

declare(strict_types=1);

namespace GryfOSS\DataTypes\Tests;

use GryfOSS\DataTypes\DataTypes;
use PHPUnit\Framework\TestCase;

class DataTypesTest extends TestCase
{
    public function testVersionConstants(): void
    {
        $this->assertIsString(DataTypes::VERSION);
        $this->assertIsInt(DataTypes::VERSION_ID);
        $this->assertEquals("1.0.34", DataTypes::VERSION);
        $this->assertEquals(10034, DataTypes::VERSION_ID);
    }

    public function testIsBitwiseValidCases(): void
    {
        $this->assertTrue(DataTypes::isBitwise("0"));
        $this->assertTrue(DataTypes::isBitwise("1"));
        $this->assertTrue(DataTypes::isBitwise("01"));
        $this->assertTrue(DataTypes::isBitwise("10"));
        $this->assertTrue(DataTypes::isBitwise("101010"));
        $this->assertTrue(DataTypes::isBitwise("11111111"));
        $this->assertTrue(DataTypes::isBitwise("00000000"));
        $this->assertTrue(DataTypes::isBitwise("0110101110101"));
    }

    public function testIsBitwiseInvalidCases(): void
    {
        $this->assertFalse(DataTypes::isBitwise(""));
        $this->assertFalse(DataTypes::isBitwise("2"));
        $this->assertFalse(DataTypes::isBitwise("a"));
        $this->assertFalse(DataTypes::isBitwise("01a"));
        $this->assertFalse(DataTypes::isBitwise("101 010"));
        $this->assertFalse(DataTypes::isBitwise("01.10"));
        $this->assertFalse(DataTypes::isBitwise("0x01"));
        $this->assertFalse(DataTypes::isBitwise(null));
        $this->assertFalse(DataTypes::isBitwise(123));
        $this->assertFalse(DataTypes::isBitwise([]));
        $this->assertFalse(DataTypes::isBitwise(true));
    }

    public function testIsBase16ValidCases(): void
    {
        $this->assertTrue(DataTypes::isBase16("0"));
        $this->assertTrue(DataTypes::isBase16("1"));
        $this->assertTrue(DataTypes::isBase16("a"));
        $this->assertTrue(DataTypes::isBase16("f"));
        $this->assertTrue(DataTypes::isBase16("A"));
        $this->assertTrue(DataTypes::isBase16("F"));
        $this->assertTrue(DataTypes::isBase16("0x0"));
        $this->assertTrue(DataTypes::isBase16("0xff"));
        $this->assertTrue(DataTypes::isBase16("0xABCDEF"));
        $this->assertTrue(DataTypes::isBase16("123456789abcdef"));
        $this->assertTrue(DataTypes::isBase16("ABCDEF0123456789"));
        $this->assertTrue(DataTypes::isBase16("deadbeef"));
        $this->assertTrue(DataTypes::isBase16("DEADBEEF"));
        $this->assertTrue(DataTypes::isBase16("0x123ABC"));
    }

    public function testIsBase16InvalidCases(): void
    {
        $this->assertFalse(DataTypes::isBase16(""));
        $this->assertFalse(DataTypes::isBase16("g"));
        $this->assertFalse(DataTypes::isBase16("G"));
        $this->assertFalse(DataTypes::isBase16("0xg"));
        $this->assertFalse(DataTypes::isBase16("123g"));
        $this->assertFalse(DataTypes::isBase16("abc xyz"));
        $this->assertFalse(DataTypes::isBase16("0x"));
        $this->assertFalse(DataTypes::isBase16("xyz"));
        $this->assertFalse(DataTypes::isBase16(null));
        $this->assertFalse(DataTypes::isBase16(123));
        $this->assertFalse(DataTypes::isBase16([]));
        $this->assertFalse(DataTypes::isBase16(true));
    }

    public function testIsHex(): void
    {
        // isHex is just an alias for isBase16
        $this->assertTrue(DataTypes::isHex("0xff"));
        $this->assertTrue(DataTypes::isHex("DEADBEEF"));
        $this->assertFalse(DataTypes::isHex("xyz"));
        $this->assertFalse(DataTypes::isHex(""));
    }

    public function testIsBase64ValidCases(): void
    {
        $this->assertTrue(DataTypes::isBase64("YWJjZA=="));
        $this->assertTrue(DataTypes::isBase64("YWJjZGU="));
        $this->assertTrue(DataTypes::isBase64("YWJjZGVm"));
        $this->assertTrue(DataTypes::isBase64("QWJjZGVm"));
        $this->assertTrue(DataTypes::isBase64("MTIzNDU2"));
        $this->assertTrue(DataTypes::isBase64("QQ=="));
        $this->assertTrue(DataTypes::isBase64("QWI="));
        $this->assertTrue(DataTypes::isBase64("QWJj"));
        $this->assertTrue(DataTypes::isBase64("A"));
        $this->assertTrue(DataTypes::isBase64("AB"));
        $this->assertTrue(DataTypes::isBase64("ABC"));
        $this->assertTrue(DataTypes::isBase64("1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz+/"));
    }

    public function testIsBase64InvalidCases(): void
    {
        $this->assertFalse(DataTypes::isBase64(""));
        $this->assertFalse(DataTypes::isBase64("QQ==="));
        $this->assertFalse(DataTypes::isBase64("QQ=Q"));
        $this->assertFalse(DataTypes::isBase64("Q Q"));
        $this->assertTrue(DataTypes::isBase64("QQ=\n")); // Newlines are valid in base64
        $this->assertFalse(DataTypes::isBase64("#"));
        $this->assertFalse(DataTypes::isBase64("$"));
        $this->assertFalse(DataTypes::isBase64("%"));
        $this->assertFalse(DataTypes::isBase64(null));
        $this->assertFalse(DataTypes::isBase64(123));
        $this->assertFalse(DataTypes::isBase64([]));
        $this->assertFalse(DataTypes::isBase64(true));
    }

    public function testIsUtf8(): void
    {
        $this->assertTrue(DataTypes::isUtf8("café"));
        $this->assertTrue(DataTypes::isUtf8("привет"));
        $this->assertTrue(DataTypes::isUtf8("🎉"));
        $this->assertTrue(DataTypes::isUtf8("こんにちは"));
        $this->assertTrue(DataTypes::isUtf8("émoji 😀"));

        $this->assertFalse(DataTypes::isUtf8("hello"));
        $this->assertFalse(DataTypes::isUtf8("123"));
        $this->assertFalse(DataTypes::isUtf8(""));
        $this->assertFalse(DataTypes::isUtf8("ASCII only"));
        $this->assertFalse(DataTypes::isUtf8(123));
        $this->assertFalse(DataTypes::isUtf8(null));
        $this->assertFalse(DataTypes::isUtf8([]));
        $this->assertFalse(DataTypes::isUtf8(true));
    }

    public function testIsNumeric(): void
    {
        $this->assertTrue(DataTypes::isNumeric(123));
        $this->assertTrue(DataTypes::isNumeric("123"));
        $this->assertTrue(DataTypes::isNumeric("123.45"));
        $this->assertTrue(DataTypes::isNumeric("-123"));
        $this->assertTrue(DataTypes::isNumeric("-123.45"));
        $this->assertTrue(DataTypes::isNumeric(0));
        $this->assertTrue(DataTypes::isNumeric("0"));
        $this->assertTrue(DataTypes::isNumeric(123.45));
        $this->assertTrue(DataTypes::isNumeric(-123.45));

        $this->assertFalse(DataTypes::isNumeric("abc"));
        $this->assertTrue(DataTypes::isNumeric("")); // Empty string returns true because it's converted to "0"
        $this->assertFalse(DataTypes::isNumeric("123abc"));
        $this->assertFalse(DataTypes::isNumeric("abc123"));
        $this->assertTrue(DataTypes::isNumeric(null)); // null is treated as numeric
        $this->assertFalse(DataTypes::isNumeric([]));
        $this->assertFalse(DataTypes::isNumeric(true));
        $this->assertFalse(DataTypes::isNumeric("123.45.67"));
        $this->assertFalse(DataTypes::isNumeric("--123"));
    }
}
