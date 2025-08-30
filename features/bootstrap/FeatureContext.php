<?php

use Behat\Behat\Context\Context;
use GryfOSS\DataTypes\BcNumber;
use GryfOSS\DataTypes\Buffer\Base16;
use GryfOSS\DataTypes\Buffer\Base64;
use GryfOSS\DataTypes\Buffer\Binary;
use GryfOSS\DataTypes\Buffer\Bitwise;
use GryfOSS\DataTypes\Buffer\Binary\ByteReader;
use GryfOSS\DataTypes\Buffer\Binary\Digest;
use GryfOSS\DataTypes\Buffer\Binary\LenSize;
use GryfOSS\DataTypes\Buffer\Base16\Decoder;
use GryfOSS\DataTypes\BcMath\BcMath;
use GryfOSS\DataTypes\BcMath\BaseConvert;
use GryfOSS\DataTypes\DataTypes;
use GryfOSS\DataTypes\Integers;
use GryfOSS\DataTypes\Strings\ASCII;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private $bcNumber;
    private $buffer;
    private $base16;
    private $base64;
    private $binary;
    private $bitwise;
    private $byteReader;
    private $digest;
    private $lenSize;
    private $decoder;
    private $result;
    private $exception;

    /**
     * @BeforeScenario
     */
    public function setUp()
    {
        $this->bcNumber = null;
        $this->buffer = null;
        $this->base16 = null;
        $this->base64 = null;
        $this->binary = null;
        $this->bitwise = null;
        $this->byteReader = null;
        $this->digest = null;
        $this->lenSize = null;
        $this->decoder = null;
        $this->result = null;
        $this->exception = null;
    }

    // BcNumber scenarios
    /**
     * @Given I have a BcNumber with value :value
     */
    public function iHaveABcNumberWithValue($value)
    {
        $this->bcNumber = new BcNumber($value);
    }

    /**
     * @When I set the scale to :scale
     */
    public function iSetTheScaleTo($scale)
    {
        $this->bcNumber->scale((int)$scale);
    }

    /**
     * @When I add :value to the number
     */
    public function iAddToTheNumber($value)
    {
        $this->result = $this->bcNumber->add($value);
    }

    /**
     * @When I subtract :value from the number
     */
    public function iSubtractFromTheNumber($value)
    {
        $this->result = $this->bcNumber->sub($value);
    }

    /**
     * @When I multiply the number by :value
     */
    public function iMultiplyTheNumberBy($value)
    {
        $this->result = $this->bcNumber->mul($value);
    }

    /**
     * @When I divide the number by :value
     */
    public function iDivideTheNumberBy($value)
    {
        $this->result = $this->bcNumber->divide($value);
    }

    /**
     * @Then the result should be :expected
     */
    public function theResultShouldBe($expected)
    {
        $actualValue = $this->result instanceof BcNumber ? $this->result->value() : $this->result;

        // Handle boolean values
        if ($expected === "true") {
            Assert::assertTrue($actualValue);
        } elseif ($expected === "false") {
            Assert::assertFalse($actualValue);
        } else {
            Assert::assertEquals($expected, $actualValue);
        }
    }

    /**
     * @Then the BcNumber value should be :expected
     */
    public function theBcNumberValueShouldBe($expected)
    {
        Assert::assertEquals($expected, $this->bcNumber->value());
    }

    // Binary Buffer scenarios
    /**
     * @Given I have a Binary buffer with data :data
     */
    public function iHaveABinaryBufferWithData($data)
    {
        $this->binary = new Binary($data);
    }

    /**
     * @When I convert the binary to Base16
     */
    public function iConvertTheBinaryToBase16()
    {
        $this->base16 = $this->binary->base16();
    }

    /**
     * @When I convert the binary to Base64
     */
    public function iConvertTheBinaryToBase64()
    {
        $this->base64 = $this->binary->base64();
    }

    /**
     * @When I convert the binary to Bitwise
     */
    public function iConvertTheBinaryToBitwise()
    {
        $this->bitwise = $this->binary->bitwise();
    }

    /**
     * @Then the Base16 hexits should be :expected
     */
    public function theBase16HexitsShouldBe($expected)
    {
        Assert::assertEquals($expected, $this->base16->hexits());
    }

    /**
     * @Then the Base64 encoded should be :expected
     */
    public function theBase64EncodedShouldBe($expected)
    {
        Assert::assertEquals($expected, $this->base64->encoded());
    }

    /**
     * @Then the Bitwise value should be :expected
     */
    public function theBitwiseValueShouldBe($expected)
    {
        Assert::assertEquals($expected, $this->bitwise->value());
    }

    // Base16 scenarios
    /**
     * @Given I have a Base16 buffer with hexits :hexits
     */
    public function iHaveABase16BufferWithHexits($hexits)
    {
        $this->base16 = new Base16($hexits);
    }

    /**
     * @When I decode the Base16 buffer
     */
    public function iDecodeTheBase16Buffer()
    {
        $this->decoder = $this->base16->decode();
    }

    /**
     * @When I convert to ASCII
     */
    public function iConvertToAscii()
    {
        $this->result = $this->decoder->ascii();
    }

    /**
     * @When I convert to base10 number
     */
    public function iConvertToBase10Number()
    {
        $this->result = $this->decoder->base10();
    }

    // ByteReader scenarios
    /**
     * @Given I have a ByteReader for binary data :data
     */
    public function iHaveAByteReaderForBinaryData($data)
    {
        $binary = new Binary($data);
        $this->byteReader = $binary->read();
    }

    /**
     * @When I read the next :bytes bytes
     */
    public function iReadTheNextBytes($bytes)
    {
        $this->result = $this->byteReader->next((int)$bytes);
    }

    /**
     * @When I reset the reader
     */
    public function iResetTheReader()
    {
        $this->byteReader->reset();
    }

    /**
     * @Then the reader position should be :position
     */
    public function theReaderPositionShouldBe($position)
    {
        Assert::assertEquals((int)$position, $this->byteReader->pos());
    }

    // Digest scenarios
    /**
     * @When I create a hash digest
     */
    public function iCreateAHashDigest()
    {
        $this->digest = $this->binary->hash();
    }

    /**
     * @When I calculate SHA256 hash
     */
    public function iCalculateSha256Hash()
    {
        $this->result = $this->digest->sha256()->raw();
    }

    // DataTypes utilities scenarios
    /**
     * @When I check if :value is bitwise
     */
    public function iCheckIfIsBitwise($value)
    {
        $this->result = DataTypes::isBitwise($value);
    }

    /**
     * @When I check if :value is Base16
     */
    public function iCheckIfIsBase16($value)
    {
        $this->result = DataTypes::isBase16($value);
    }

    /**
     * @When I check if :value is UTF8
     */
    public function iCheckIfIsUtf8($value)
    {
        $this->result = DataTypes::isUtf8($value);
    }

    // Integers utility scenarios
    /**
     * @When I check if :number is in range :from to :to
     */
    public function iCheckIfIsInRangeTo($number, $from, $to)
    {
        $this->result = Integers::Range((int)$number, (int)$from, (int)$to);
    }

    // ASCII utility scenarios
    /**
     * @When I encode :text to Base16 ASCII
     */
    public function iEncodeToBase16Ascii($text)
    {
        $this->result = ASCII::base16Encode($text);
    }

    /**
     * @When I decode Base16 ASCII
     */
    public function iDecodeBase16Ascii()
    {
        $this->result = ASCII::base16Decode($this->base16);
    }

    // BcMath scenarios
    /**
     * @When I encode number :number to Base16
     */
    public function iEncodeNumberToBase16($number)
    {
        $this->result = BcMath::Encode($number);
    }

    /**
     * @When I decode hexits :hexits to decimal
     */
    public function iDecodeHexitsToDecimal($hexits)
    {
        $this->result = BcMath::Decode($hexits);
    }

    /**
     * @When I convert :value from base :fromBase to base :toBase
     */
    public function iConvertFromBaseToBase($value, $fromBase, $toBase)
    {
        $this->result = BcMath::BaseConvert($value, (int)$fromBase, (int)$toBase);
    }

    // Exception handling
    /**
     * @When I try to encode UTF8 text :text to Base16
     */
    public function iTryToEncodeUtf8TextToBase16($text)
    {
        try {
            ASCII::base16Encode($text);
        } catch (\Exception $e) {
            $this->exception = $e;
        }
    }

    /**
     * @Then an InvalidArgumentException should be thrown
     */
    public function anInvalidArgumentExceptionShouldBeThrown()
    {
        Assert::assertInstanceOf(\InvalidArgumentException::class, $this->exception);
    }

    /**
     * @Then the exception message should contain :message
     */
    public function theExceptionMessageShouldContain($message)
    {
        Assert::assertStringContainsString($message, $this->exception->getMessage());
    }

    // Buffer operations
    /**
     * @When I append :data to the buffer
     */
    public function iAppendToTheBuffer($data)
    {
        $this->binary->append($data);
    }

    /**
     * @When I prepend :data to the buffer
     */
    public function iPrependToTheBuffer($data)
    {
        $this->binary->prepend($data);
    }

    /**
     * @When I set the buffer to read-only
     */
    public function iSetTheBufferToReadOnly()
    {
        $this->binary->readOnly(true);
    }

    /**
     * @When I try to modify the read-only buffer
     */
    public function iTryToModifyTheReadOnlyBuffer()
    {
        try {
            $this->binary->set("new data");
        } catch (\Exception $e) {
            $this->exception = $e;
        }
    }

    /**
     * @Then a BadMethodCallException should be thrown
     */
    public function aBadMethodCallExceptionShouldBeThrown()
    {
        Assert::assertInstanceOf(\BadMethodCallException::class, $this->exception);
    }

    /**
     * @Then the buffer value should be :expected
     */
    public function theBufferValueShouldBe($expected)
    {
        Assert::assertEquals($expected, $this->binary->value());
    }

    /**
     * @Then the buffer length should be :expected
     */
    public function theBufferLengthShouldBe($expected)
    {
        Assert::assertEquals((int)$expected, $this->binary->len());
    }

    // Additional step definitions
    /**
     * @Then the result should be a Base16 object
     */
    public function theResultShouldBeABase16Object()
    {
        Assert::assertInstanceOf(Base16::class, $this->result);
    }

    /**
     * @Then the result should be a hash value
     */
    public function theResultShouldBeAHashValue()
    {
        Assert::assertIsString($this->result);
        Assert::assertNotEmpty($this->result);
    }

    /**
     * @When I check if the result contains hexadecimal characters
     */
    public function iCheckIfTheResultContainsHexadecimalCharacters()
    {
        $this->result = DataTypes::isBase16($this->result);
    }
}
