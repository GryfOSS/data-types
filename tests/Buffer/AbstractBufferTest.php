<?php

declare(strict_types=1);

namespace GryfOSS\DataTypes\Tests\Buffer;

use GryfOSS\DataTypes\Buffer\Base16;
use GryfOSS\DataTypes\Buffer\Binary;
use PHPUnit\Framework\TestCase;

class AbstractBufferTest extends TestCase
{
    public function testConstructorWithNull(): void
    {
        $buffer = new Binary(null);
        $this->assertEquals("", $buffer->value());
        $this->assertEquals(0, $buffer->len());
    }

    public function testConstructorWithString(): void
    {
        $buffer = new Binary("test");
        $this->assertEquals("test", $buffer->value());
        $this->assertEquals(4, $buffer->len());
    }

    public function testMagicProperties(): void
    {
        $buffer = new Binary("test");
        $this->assertEquals(4, $buffer->sizeInBytes);
        $this->assertEquals(4, $buffer->length);
    }

    public function testInvalidProperty(): void
    {
        $buffer = new Binary("test");
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('Cannot get value of inaccessible property');
        $unused = $buffer->invalidProperty;
    }

    public function testSerialization(): void
    {
        $buffer = new Binary("test");
        $serialized = $buffer->serialize();
        $this->assertIsString($serialized);

        $newBuffer = new Binary();
        $newBuffer->unserialize($serialized);
        $this->assertEquals("test", $newBuffer->value());
        $this->assertEquals(4, $newBuffer->sizeInBytes);
    }

    public function testSerializationWithReadOnly(): void
    {
        $buffer = new Binary("test");
        $buffer->readOnly(true);
        $serialized = $buffer->serialize();

        $newBuffer = new Binary();
        $newBuffer->unserialize($serialized);
        $this->assertEquals("test", $newBuffer->value());

        // Read-only state is not preserved during serialization
        $newBuffer->set("new data");
        $this->assertEquals("new data", $newBuffer->value());
    }

    public function testUnserializeInvalidData(): void
    {
        $buffer = new Binary();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Serialized data mismatch');
        $buffer->unserialize("invalid");
    }

    public function testUnserializeWrongSize(): void
    {
        $buffer = new Binary();
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Serialized data size does not match');
        $buffer->unserialize("0:10:dGVzdA=="); // Size says 10 but "test" is 4 bytes
    }

    public function testReadOnly(): void
    {
        $buffer = new Binary("test");
        $result = $buffer->readOnly(true);
        $this->assertSame($buffer, $result);

        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Buffer is in read-only state');
        $buffer->set("new data");
    }

    public function testReadOnlyDisable(): void
    {
        $buffer = new Binary("test");
        $buffer->readOnly(true);
        $buffer->readOnly(false);

        // Should not throw exception
        $buffer->set("new data");
        $this->assertEquals("new data", $buffer->value());
    }

    public function testSet(): void
    {
        $buffer = new Binary();
        $result = $buffer->set("test");
        $this->assertSame($buffer, $result);
        $this->assertEquals("test", $buffer->value());
    }

    public function testSetWithNull(): void
    {
        $buffer = new Binary("existing");
        $buffer->set(null);
        $this->assertEquals("existing", $buffer->value()); // null doesn't change the value

        // Test setting empty string - also doesn't change the value
        $buffer->set("");
        $this->assertEquals("existing", $buffer->value());
    }

    public function testAppendString(): void
    {
        $buffer = new Binary("test");
        $result = $buffer->append("123");
        $this->assertSame($buffer, $result);
        $this->assertEquals("test123", $buffer->value());
    }

    public function testAppendBuffer(): void
    {
        $buffer1 = new Binary("test");
        $buffer2 = new Binary("123");
        $buffer1->append($buffer2);
        $this->assertEquals("test123", $buffer1->value());
    }

    public function testAppendInvalidType(): void
    {
        $buffer = new Binary("test");
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Appending data must be of type String or a Buffer');
        $buffer->append(123);
    }

    public function testPrependString(): void
    {
        $buffer = new Binary("test");
        $result = $buffer->prepend("123");
        $this->assertSame($buffer, $result);
        $this->assertEquals("123test", $buffer->value());
    }

    public function testPrependBuffer(): void
    {
        $buffer1 = new Binary("test");
        $buffer2 = new Binary("123");
        $buffer1->prepend($buffer2);
        $this->assertEquals("123test", $buffer1->value());
    }

    public function testPrependInvalidType(): void
    {
        $buffer = new Binary("test");
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Prepend data must be of type String or a Buffer');
        $buffer->prepend(123);
    }

    public function testValueWithStart(): void
    {
        $buffer = new Binary("testing");
        $result = $buffer->value(2);
        $this->assertEquals("sting", $result);
    }

    public function testValueWithStartAndLength(): void
    {
        $buffer = new Binary("testing");
        $result = $buffer->value(2, 3);
        $this->assertEquals("sti", $result);
    }

    public function testValueWithInvalidRange(): void
    {
        $buffer = new Binary("test");
        $result = $buffer->value(10);
        $this->assertEquals("", $result); // Returns empty string for invalid range
    }

    public function testCopy(): void
    {
        $buffer = new Binary("testing");
        $copy = $buffer->copy();
        $this->assertNotSame($buffer, $copy);
        $this->assertEquals("testing", $copy->value());
    }

    public function testCopyWithRange(): void
    {
        $buffer = new Binary("testing");
        $copy = $buffer->copy(2, 3);
        $this->assertEquals("sti", $copy->value());
    }

    public function testClone(): void
    {
        $buffer = new Binary("testing");
        $clone = $buffer->clone();
        $this->assertNotSame($buffer, $clone);
        $this->assertEquals("testing", $clone->value());
    }

    public function testMagicClone(): void
    {
        $buffer = new Binary("testing");
        $clone = clone $buffer;
        $this->assertNotSame($buffer, $clone);
        $this->assertEquals("testing", $clone->value());
    }

    public function testSubstr(): void
    {
        $buffer = new Binary("testing");
        $result = $buffer->substr(2);
        $this->assertSame($buffer, $result);
        $this->assertEquals("sting", $buffer->value());
    }

    public function testSubstrWithLength(): void
    {
        $buffer = new Binary("testing");
        $buffer->substr(2, 3);
        $this->assertEquals("sti", $buffer->value());
    }

    public function testSubstrWithNoArguments(): void
    {
        $buffer = new Binary("testing");
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Both start/end arguments cannot be empty');
        $buffer->substr();
    }

    public function testSubstrFailure(): void
    {
        $buffer = new Binary("test");
        // substr with invalid range returns the original buffer, no exception
        $result = $buffer->substr(10, 5);
        $this->assertEquals("test", $result->value());
    }

    public function testEquals(): void
    {
        $buffer1 = new Binary("test");
        $buffer2 = new Binary("test");
        $buffer3 = new Binary("different");
        $buffer4 = new Base16("74657374"); // "test" in hex

        $this->assertTrue($buffer1->equals($buffer2));
        $this->assertFalse($buffer1->equals($buffer3));
        $this->assertFalse($buffer1->equals($buffer4)); // Different classes
    }

    public function testApply(): void
    {
        $buffer = new Binary("test");
        $buffer->apply(function($data) {
            return strtoupper($data);
        });
        $this->assertEquals("TEST", $buffer->value());
    }

    public function testApplyWithInvalidReturn(): void
    {
        $buffer = new Binary("test");
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('Callback method supplied to "apply" method must return String');
        $buffer->apply(function($data) {
            return 123;
        });
    }
}
