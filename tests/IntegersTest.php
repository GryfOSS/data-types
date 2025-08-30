<?php

declare(strict_types=1);

namespace GryfOSS\DataTypes\Tests;

use GryfOSS\DataTypes\Integers;
use PHPUnit\Framework\TestCase;

class IntegersTest extends TestCase
{
    public function testRangeValidCases(): void
    {
        $this->assertTrue(Integers::Range(5, 1, 10));
        $this->assertTrue(Integers::Range(1, 1, 10));
        $this->assertTrue(Integers::Range(10, 1, 10));
        $this->assertTrue(Integers::Range(0, 0, 0));
        $this->assertTrue(Integers::Range(-5, -10, 0));
        $this->assertTrue(Integers::Range(-10, -10, -5));
        $this->assertTrue(Integers::Range(100, 50, 150));
    }

    public function testRangeInvalidCases(): void
    {
        $this->assertFalse(Integers::Range(0, 1, 10));
        $this->assertFalse(Integers::Range(11, 1, 10));
        $this->assertFalse(Integers::Range(-1, 0, 10));
        $this->assertFalse(Integers::Range(15, 1, 10));
        $this->assertFalse(Integers::Range(-15, -10, -5));
        $this->assertFalse(Integers::Range(5, 10, 20));
        $this->assertFalse(Integers::Range(25, 10, 20));
    }

    public function testRangeEdgeCases(): void
    {
        $this->assertTrue(Integers::Range(PHP_INT_MAX, PHP_INT_MAX, PHP_INT_MAX));
        $this->assertTrue(Integers::Range(PHP_INT_MIN, PHP_INT_MIN, PHP_INT_MIN));
        $this->assertTrue(Integers::Range(0, PHP_INT_MIN, PHP_INT_MAX));
        $this->assertTrue(Integers::Range(PHP_INT_MAX, 0, PHP_INT_MAX));
        $this->assertTrue(Integers::Range(PHP_INT_MIN, PHP_INT_MIN, 0));
    }
}
