<?php

declare(strict_types=1);

namespace GryfOSS\DataTypes\Tests\Buffer\Binary;

use GryfOSS\DataTypes\Buffer\Binary;
use GryfOSS\DataTypes\Buffer\Binary\ByteReader;
use PHPUnit\Framework\TestCase;

class ByteReaderTest extends TestCase
{
    public function testConstructor(): void
    {
        $binary = new Binary("Hello");
        $reader = new ByteReader($binary);
        $this->assertInstanceOf(ByteReader::class, $reader);
    }

    public function testIsEnd(): void
    {
        $binary = new Binary("Hi");
        $reader = new ByteReader($binary);

        $this->assertFalse($reader->isEnd());
        $reader->next(2);
        $this->assertTrue($reader->isEnd());
    }

    public function testThrowUnderflowEx(): void
    {
        $binary = new Binary("Hi");
        $reader = new ByteReader($binary);
        $result = $reader->throwUnderflowEx();

        $this->assertSame($reader, $result);
    }

    public function testLen(): void
    {
        $binary = new Binary("Hello");
        $reader = new ByteReader($binary);
        $this->assertEquals(5, $reader->len());
    }

    public function testPos(): void
    {
        $binary = new Binary("Hello");
        $reader = new ByteReader($binary);
        $this->assertEquals(0, $reader->pos());

        $reader->next(2);
        $this->assertEquals(2, $reader->pos());
    }

    public function testReset(): void
    {
        $binary = new Binary("Hello");
        $reader = new ByteReader($binary);
        $reader->next(3);
        $this->assertEquals(3, $reader->pos());

        $result = $reader->reset();
        $this->assertSame($reader, $result);
        $this->assertEquals(0, $reader->pos());
    }

    public function testFirst(): void
    {
        $binary = new Binary("Hello");
        $reader = new ByteReader($binary);
        $reader->next(3); // Move pointer

        $result = $reader->first(2);
        $this->assertEquals("He", $result);
        $this->assertEquals(2, $reader->pos()); // Should reset and then read
    }

    public function testNext(): void
    {
        $binary = new Binary("Hello");
        $reader = new ByteReader($binary);

        $result = $reader->next(2);
        $this->assertEquals("He", $result);
        $this->assertEquals(2, $reader->pos());

        $result2 = $reader->next(3);
        $this->assertEquals("llo", $result2);
        $this->assertEquals(5, $reader->pos());
    }

    public function testNextAtEnd(): void
    {
        $binary = new Binary("Hi");
        $reader = new ByteReader($binary);
        $reader->next(2);

        $result = $reader->next(1);
        $this->assertNull($result);
    }

    public function testNextWithUnderflowException(): void
    {
        $binary = new Binary("Hi");
        $reader = new ByteReader($binary);
        $reader->throwUnderflowEx();

        $this->expectException(\UnderflowException::class);
        $this->expectExceptionCode(ByteReader::UNDERFLOW_EX_SIGNAL);
        $this->expectExceptionMessage('Attempt to read next 5 bytes, while only 2 available');
        $reader->next(5);
    }

    public function testNextPartialReadWithUnderflow(): void
    {
        $binary = new Binary("Hi");
        $reader = new ByteReader($binary);
        $reader->throwUnderflowEx();
        $reader->next(1); // Read 1 byte, leaving 1

        $this->expectException(\UnderflowException::class);
        $this->expectExceptionCode(ByteReader::UNDERFLOW_EX_SIGNAL);
        $this->expectExceptionMessage('Attempt to read next 2 bytes, while only 1 available');
        $reader->next(2); // Try to read 2 more
    }

    public function testSetPointer(): void
    {
        $binary = new Binary("Hello");
        $reader = new ByteReader($binary);

        $result = $reader->setPointer(3);
        $this->assertSame($reader, $result);
        $this->assertEquals(3, $reader->pos());
    }

    public function testSetPointerOutOfRange(): void
    {
        $binary = new Binary("Hello");
        $reader = new ByteReader($binary);

        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('Invalid pointer position or is out of range');
        $reader->setPointer(10);
    }

    public function testSetPointerNegative(): void
    {
        $binary = new Binary("Hello");
        $reader = new ByteReader($binary);

        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('Invalid pointer position or is out of range');
        $reader->setPointer(-1);
    }

    public function testRemaining(): void
    {
        $binary = new Binary("Hello");
        $reader = new ByteReader($binary);

        $this->assertEquals("Hello", $reader->remaining());

        $reader->next(2);
        $this->assertEquals("llo", $reader->remaining());

        $reader->next(3);
        $this->assertNull($reader->remaining());
    }

    public function testComplexSequence(): void
    {
        $binary = new Binary("Hello World");
        $reader = new ByteReader($binary);

        // Read "Hell"
        $this->assertEquals("Hell", $reader->next(4));
        $this->assertEquals(4, $reader->pos());

        // Read "o W"
        $this->assertEquals("o W", $reader->next(3));
        $this->assertEquals(7, $reader->pos());

        // Remaining should be "orld"
        $this->assertEquals("orld", $reader->remaining());

        // Reset and try first
        $this->assertEquals("He", $reader->first(2));
        $this->assertEquals(2, $reader->pos());
    }

    public function testEmptyBinary(): void
    {
        $binary = new Binary("");
        $reader = new ByteReader($binary);

        $this->assertEquals(0, $reader->len());
        $this->assertTrue($reader->isEnd());
        $this->assertNull($reader->next(1));
        $this->assertNull($reader->remaining());
    }

    public function testBinaryData(): void
    {
        $binaryData = "\x00\x01\x02\x03\xFF";
        $binary = new Binary($binaryData);
        $reader = new ByteReader($binary);

        $this->assertEquals("\x00\x01", $reader->next(2));
        $this->assertEquals("\x02\x03\xFF", $reader->remaining());
    }

    public function testNextWithExactBytes(): void
    {
        $binary = new Binary("Hello");
        $reader = new ByteReader($binary);

        // Test reading exact number of bytes
        $result = $reader->next(5);
        $this->assertEquals("Hello", $result);
        $this->assertEquals(5, $reader->pos());
    }

    public function testNextWithPartialRead(): void
    {
        $binary = new Binary("Hi");
        $reader = new ByteReader($binary);
        $reader->next(1); // Read 1 byte

        // Try to read more than available without underflow exception
        $result = $reader->next(5);
        $this->assertNull($result);
    }

    public function testNextWithEmptyResult(): void
    {
        $binary = new Binary("");
        $reader = new ByteReader($binary);

        $result = $reader->next(1);
        $this->assertNull($result);
    }

    public function testNextPartialReadWithLessBytes(): void
    {
        // Create a scenario where substr returns a string but with fewer bytes than requested
        $binary = new Binary("Hi");
        $reader = new ByteReader($binary);
        $reader->next(1); // Read 1 byte, leaving only 1 byte

        // Try to read 2 bytes when only 1 is available - should return null
        $result = $reader->next(2);
        $this->assertNull($result);
    }

    public function testNextPartialReadWithUnderflowException(): void
    {
        // Test the scenario where we try to read beyond available bytes with underflow enabled
        $binary = new Binary("Hi");
        $reader = new ByteReader($binary);
        $reader->throwUnderflowEx();
        $reader->next(1); // Read 1 byte, leaving only 1 byte

        $this->expectException(\UnderflowException::class);
        $this->expectExceptionCode(ByteReader::UNDERFLOW_EX_SIGNAL);
        $this->expectExceptionMessage('Attempt to read next 2 bytes, while only 1 available');
        $reader->next(2); // Try to read 2 bytes when only 1 is available
    }

    public function testNextWithSubstrFailure(): void
    {
        // Test scenario where we try to read beyond buffer bounds
        $binary = new Binary("test");
        $reader = new ByteReader($binary);
        $reader->next(4); // Read all 4 bytes

        // Now try to read more - should return null
        $result = $reader->next(1);
        $this->assertNull($result);
    }
}
