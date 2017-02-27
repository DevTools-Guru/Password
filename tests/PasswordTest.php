<?php

namespace DevToolsGuru\Password\Tests;

use DevToolsGuru\Password;
use DevToolsGuru\Password\ExcessiveLengthException;

class PasswordTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        Password::$errorOnExcessiveLength = true;
    }

    public function testCreationFromNonHash()
    {
        $password = new Password('BadPassword');

        self::assertNotSame('BadPassword', $password->getHash());
    }

    public function testExceptionForLongValue()
    {
        $this->expectException(ExcessiveLengthException::class);

        $range = range(1, 80);
        new Password(implode('', $range));
    }

    public function testExceptionBypass()
    {
        Password::$errorOnExcessiveLength = false;

        $range = implode('', range(1, 80));
        $password = new Password($range);

        self::assertNotSame($range, $password->getHash());
    }

    public function testRehashDetection()
    {
        $password = new Password('BadPassword');
        self::assertTrue($password->needsRehash(12));
        self::assertFalse($password->needsRehash(10));
    }

    public function testVerificationWithPlainText()
    {
        // Precomputed hash onetime locally for 'BadPassword'
        $password = new Password('$2y$10$Xt/lbzcK5pQSSfYWVca54OVJbhh3uNLJEPzdBsJOYHWk7GEMryZf.');
        self::assertTrue($password->verify('BadPassword'));
        self::assertFalse($password->verify('NotThePassword'));
    }

    public function testAlgorithmIsInterpreted()
    {
        $password = new Password('hello');
        self::assertSame(PASSWORD_DEFAULT, $password->getInfo()->getAlgorithm());
    }

    public function testCostIsReturned()
    {
        $password = new Password('hello');
        self::assertSame(10, $password->getInfo()->getCost());

        $password = new Password('hello', 15);
        $testPassword = new Password($password->getHash());
        self::assertSame(15, $testPassword->getInfo()->getCost());
    }

    public function testAlgorithmNameIsReturned()
    {
        $password = new Password('hello');
        self::assertSame('bcrypt', $password->getInfo()->getAlgorithmName());
    }
}
