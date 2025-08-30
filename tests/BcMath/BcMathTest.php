<?php

declare(strict_types=1);

namespace GryfOSS\DataTypes\Tests\BcMath;

use GryfOSS\DataTypes\BcMath\BcMath;
use GryfOSS\DataTypes\BcNumber;
use PHPUnit\Framework\TestCase;

class BcMathTest extends TestCase
{
    public function testEncodeValidIntegerString(): void
    {
        $result = BcMath::Encode("255");
        $this->assertEquals("ff", $result);
    }

    public function testEncodeValidInteger(): void
    {
        $result = BcMath::Encode(255);
        $this->assertEquals("ff", $result);
    }

    public function testEncodeWithPrefix(): void
    {
        $result = BcMath::Encode("255", true);
        $this->assertEquals("0xff", $result);
    }

    public function testEncodeWithOddHexits(): void
    {
        $result = BcMath::Encode("15"); // Should result in "f" which gets padded to "0f"
        $this->assertEquals("0f", $result);
    }

    public function testEncodeZero(): void
    {
        $result = BcMath::Encode("0");
        $this->assertEquals("00", $result);
    }

    public function testEncodeInvalidInput(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('First argument must be an integral number');
        BcMath::Encode("123.45");
    }

    public function testEncodeInvalidString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('First argument must be an integral number');
        BcMath::Encode("abc");
    }

    public function testDecodeValidHexits(): void
    {
        $result = BcMath::Decode("ff");
        $this->assertEquals("255", $result);
    }

    public function testDecodeWithPrefix(): void
    {
        $result = BcMath::Decode("0xff");
        $this->assertEquals("255", $result);
    }

    public function testDecodeUpperCase(): void
    {
        $result = BcMath::Decode("FF");
        $this->assertEquals("255", $result);
    }

    public function testDecodeMixedCase(): void
    {
        $result = BcMath::Decode("DeAdBeEf");
        $this->assertEquals("3735928559", $result);
    }

    public function testDecodeZero(): void
    {
        $result = BcMath::Decode("0");
        $this->assertEquals("0", $result);
    }

    public function testDecodeInvalidInput(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Only hexadecimal numbers can be decoded');
        BcMath::Decode("xyz");
    }

    public function testBaseConvertSameBases(): void
    {
        $result = BcMath::BaseConvert("123", 10, 10);
        $this->assertEquals("123", $result);
    }

    public function testBaseConvertBinaryToDecimal(): void
    {
        $result = BcMath::BaseConvert("1010", 2, 10);
        $this->assertEquals("10", $result);
    }

    public function testBaseConvertDecimalToBinary(): void
    {
        $result = BcMath::BaseConvert("10", 10, 2);
        $this->assertEquals("1010", $result);
    }

    public function testBaseConvertHexToDecimal(): void
    {
        $result = BcMath::BaseConvert("ff", 16, 10);
        $this->assertEquals("255", $result);
    }

    public function testBaseConvertDecimalToHex(): void
    {
        $result = BcMath::BaseConvert("255", 10, 16);
        $this->assertEquals("ff", $result);
    }

    public function testBaseConvertCaseInsensitive(): void
    {
        $result = BcMath::BaseConvert("FF", 16, 10);
        $this->assertEquals("255", $result);
    }

    public function testIsNumericWithValidNumbers(): void
    {
        $this->assertInstanceOf(BcNumber::class, BcMath::isNumeric(123));
        $this->assertInstanceOf(BcNumber::class, BcMath::isNumeric("123"));
        $this->assertInstanceOf(BcNumber::class, BcMath::isNumeric("123.45"));
        $this->assertInstanceOf(BcNumber::class, BcMath::isNumeric(-123));
        $this->assertInstanceOf(BcNumber::class, BcMath::isNumeric("-123"));
        $this->assertInstanceOf(BcNumber::class, BcMath::isNumeric(123.45));
        $this->assertInstanceOf(BcNumber::class, BcMath::isNumeric(0));
        $this->assertInstanceOf(BcNumber::class, BcMath::isNumeric("0"));
    }

    public function testIsNumericWithInvalidNumbers(): void
    {
        $this->assertNull(BcMath::isNumeric("abc"));
        $this->assertInstanceOf(BcNumber::class, BcMath::isNumeric("")); // Empty string returns "0"
        $this->assertNull(BcMath::isNumeric("123abc"));
        $this->assertNull(BcMath::isNumeric("abc123"));
        $this->assertInstanceOf(BcNumber::class, BcMath::isNumeric(null)); // null returns "0"
        $this->assertInstanceOf(BcNumber::class, BcMath::isNumeric([])); // array returns "0"
        $this->assertNull(BcMath::isNumeric(true));
    }

    public function testValueWithBcNumber(): void
    {
        $bcNumber = new BcNumber(123);
        $result = BcMath::Value($bcNumber);
        $this->assertEquals("123", $result);
    }

    public function testValueWithInteger(): void
    {
        $result = BcMath::Value(123);
        $this->assertEquals("123", $result);
    }

    public function testValueWithFloat(): void
    {
        $result = BcMath::Value(123.45);
        $this->assertEquals("123.45", $result);
    }

    public function testValueWithFloatScientificNotationNegative(): void
    {
        $result = BcMath::Value(1.23e-4);
        $this->assertStringContainsString("0.000123", $result);
    }

    public function testValueWithFloatScientificNotationPositive(): void
    {
        $result = BcMath::Value(1.23e4);
        $this->assertEquals("12300", $result);
    }

    public function testValueWithValidString(): void
    {
        $result = BcMath::Value("123.45");
        $this->assertEquals("123.45", $result);
    }

    public function testValueWithNegativeString(): void
    {
        $result = BcMath::Value("-123.45");
        $this->assertEquals("-123.45", $result);
    }

    public function testValueWithZeroString(): void
    {
        $result = BcMath::Value("0");
        $this->assertEquals("0", $result);
    }

    public function testValueWithInvalidString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Passed value cannot be used as number with BcMath lib');
        BcMath::Value("abc");
    }

    public function testValueWithEmptyString(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Passed value cannot be used as number with BcMath lib');
        BcMath::Value("");
    }

    public function testValueWithInvalidType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Passed value cannot be used as number with BcMath lib');
        BcMath::Value([]);
    }
}
