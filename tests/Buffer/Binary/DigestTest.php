<?php

declare(strict_types=1);

namespace GryfOSS\DataTypes\Tests\Buffer\Binary;

use GryfOSS\DataTypes\Buffer\Base16;
use GryfOSS\DataTypes\Buffer\Binary;
use GryfOSS\DataTypes\Buffer\Binary\Digest;
use PHPUnit\Framework\TestCase;

class DigestTest extends TestCase
{
    public function testConstructor(): void
    {
        $binary = new Binary("Hello");
        $digest = new Digest($binary);
        $this->assertInstanceOf(Digest::class, $digest);
    }

    public function testMd5(): void
    {
        $binary = new Binary("Hello");
        $digest = new Digest($binary);
        $result = $digest->md5();

        $this->assertInstanceOf(Binary::class, $result);
        $expected = md5("Hello", true);
        $this->assertEquals($expected, $result->raw());
    }

    public function testMd5WithBytes(): void
    {
        $binary = new Binary("Hello");
        $digest = new Digest($binary);
        $result = $digest->md5(8);

        $this->assertInstanceOf(Binary::class, $result);
        $this->assertEquals(8, $result->size()->bytes());
    }

    public function testSha1(): void
    {
        $binary = new Binary("Hello");
        $digest = new Digest($binary);
        $result = $digest->sha1();

        $this->assertInstanceOf(Binary::class, $result);
        $expected = sha1("Hello", true);
        $this->assertEquals($expected, $result->raw());
    }

    public function testSha1WithBytes(): void
    {
        $binary = new Binary("Hello");
        $digest = new Digest($binary);
        $result = $digest->sha1(10);

        $this->assertInstanceOf(Binary::class, $result);
        $this->assertEquals(10, $result->size()->bytes());
    }

    public function testSha256(): void
    {
        $binary = new Binary("Hello");
        $digest = new Digest($binary);
        $result = $digest->sha256();

        $this->assertInstanceOf(Binary::class, $result);
        $expected = hash('sha256', "Hello", true);
        $this->assertEquals($expected, $result->raw());
    }

    public function testSha256WithBytes(): void
    {
        $binary = new Binary("Hello");
        $digest = new Digest($binary);
        $result = $digest->sha256(16);

        $this->assertInstanceOf(Binary::class, $result);
        $this->assertEquals(16, $result->size()->bytes());
    }

    public function testRipeMd160(): void
    {
        $binary = new Binary("Hello");
        $digest = new Digest($binary);
        $result = $digest->ripeMd160();

        $this->assertInstanceOf(Binary::class, $result);
        $expected = hash('ripemd160', "Hello", true);
        $this->assertEquals($expected, $result->raw());
    }

    public function testRipeMd160WithBytes(): void
    {
        $binary = new Binary("Hello");
        $digest = new Digest($binary);
        $result = $digest->ripeMd160(10);

        $this->assertInstanceOf(Binary::class, $result);
        $this->assertEquals(10, $result->size()->bytes());
    }

    public function testDigestWithCustomAlgorithm(): void
    {
        $binary = new Binary("Hello");
        $digest = new Digest($binary);
        $result = $digest->digest('md5', 1, 0);

        $this->assertInstanceOf(Binary::class, $result);
        $expected = md5("Hello", true);
        $this->assertEquals($expected, $result->raw());
    }

    public function testDigestWithIterations(): void
    {
        $binary = new Binary("Hello");
        $digest = new Digest($binary);
        $result = $digest->digest('md5', 2, 0);

        $this->assertInstanceOf(Binary::class, $result);
        $expected = md5(md5("Hello", true), true);
        $this->assertEquals($expected, $result->raw());
    }

    public function testDigestWithBytesLimit(): void
    {
        $binary = new Binary("Hello");
        $digest = new Digest($binary);
        $result = $digest->digest('sha256', 1, 10);

        $this->assertInstanceOf(Binary::class, $result);
        $this->assertEquals(10, $result->size()->bytes());
    }

    public function testPbkdf2WithStringKey(): void
    {
        $binary = new Binary("password");
        $digest = new Digest($binary);
        $result = $digest->pbkdf2('sha256', 'salt', 1000, 32);

        $this->assertInstanceOf(Binary::class, $result);
        $this->assertEquals(32, $result->size()->bytes());

        $expected = hash_pbkdf2('sha256', 'password', 'salt', 1000, 32, true);
        $this->assertEquals($expected, $result->raw());
    }

    public function testPbkdf2WithBinarySalt(): void
    {
        $binary = new Binary("password");
        $salt = new Binary("salt");
        $digest = new Digest($binary);
        $result = $digest->pbkdf2('sha256', $salt, 1000, 32);

        $this->assertInstanceOf(Binary::class, $result);
        $this->assertEquals(32, $result->size()->bytes());
    }

    public function testPbkdf2WithInvalidSalt(): void
    {
        $binary = new Binary("password");
        $digest = new Digest($binary);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid value for PBKDF2 param "salt"');
        $digest->pbkdf2('sha256', 123, 1000, 32);
    }

    public function testHmacWithStringKey(): void
    {
        $binary = new Binary("message");
        $digest = new Digest($binary);
        $result = $digest->hmac('sha256', 'key');

        $this->assertInstanceOf(Binary::class, $result);
        $expected = hash_hmac('sha256', 'message', 'key', true);
        $this->assertEquals($expected, $result->raw());
    }

    public function testHmacWithBinaryKey(): void
    {
        $binary = new Binary("message");
        $key = new Binary("key");
        $digest = new Digest($binary);
        $result = $digest->hmac('sha256', $key);

        $this->assertInstanceOf(Binary::class, $result);
        $expected = hash_hmac('sha256', 'message', 'key', true);
        $this->assertEquals($expected, $result->raw());
    }

    public function testHmacWithBase16Key(): void
    {
        $binary = new Binary("message");
        $key = new Base16("6b6579"); // "key" in hex
        $digest = new Digest($binary);
        $result = $digest->hmac('sha256', $key);

        $this->assertInstanceOf(Binary::class, $result);
        $expected = hash_hmac('sha256', 'message', 'key', true);
        $this->assertEquals($expected, $result->raw());
    }

    public function testHmacWithInvalidKey(): void
    {
        $binary = new Binary("message");
        $digest = new Digest($binary);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid value for HMAC param "key"');
        $digest->hmac('sha256', 123);
    }
}
