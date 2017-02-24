<?php

namespace DevToolsGuru;

final class Password
{
    const AVAILABLE_ALGORITHMS = [
        PASSWORD_DEFAULT,
        PASSWORD_BCRYPT,
    ];

    const MAX_LENGTHS = [
        PASSWORD_BCRYPT => 72,
    ];

    /** @var bool $errorOnExcessiveLength */
    public static $errorOnExcessiveLength = true;

    /** @var string $hash */
    private $hash;

    /** @var int $cost */
    private $cost;

    /** @var int $algorithm */
    private $algorithm;

    /**
     * Create a new password from the provided $value.
     * You may provide a $cost to increase the difficulty when making a new hash if needed.
     *
     * @param string $value
     * @param int $cost
     *
     * @throws \DevToolsGuru\Password\ExcessiveLengthException
     */
    public function __construct($value, $cost = 10)
    {
        if (in_array(password_get_info($value)['algo'], array_values(self::AVAILABLE_ALGORITHMS), true)) {
            $this->setProps($value);

            return;
        }

        if (self::$errorOnExcessiveLength &&
            self::hasMaxLength(PASSWORD_DEFAULT) &&
            strlen($value) >= self::MAX_LENGTHS[PASSWORD_DEFAULT]
        ) {
            $exception = new Password\ExcessiveLengthException();
            $exception->setMaxLength(self::MAX_LENGTHS[PASSWORD_DEFAULT]);
            throw $exception;
        }

        $this->setProps(password_hash($value, PASSWORD_DEFAULT, ['cost' => $cost]));
    }

    /**
     * Helper method for setting the values when creating the object.
     *
     * @param string $value
     */
    private function setProps($value)
    {
        $info = password_get_info($value);
        $this->hash = $value;
        $this->cost = $info['options']['cost'] ?? 10;
        $this->algorithm = $info['algo'];
    }

    /**
     * Get the full hash of the password.
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Get the cost used to hash the password.
     *
     * @return int
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Get the algorithm identifier used to hash the password.
     *
     * @return int
     */
    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    /**
     * Verify the stored hash is equal to the provided value.
     *
     * @param string $plainText
     *
     * @return bool
     */
    public function verify($plainText)
    {
        return password_verify($plainText, $this->hash);
    }

    /**
     * See if the stored hash needs to be rehashed.
     * This can be because the given cost is greater
     * or because it is no longer hashed with the default algorithm.
     *
     * @param int $cost
     *
     * @return bool
     */
    public function needsRehash($cost = 10)
    {
        return password_needs_rehash($this->hash, PASSWORD_DEFAULT, ['cost' => $cost]);
    }

    /**
     * See if the given algorithm has a known length
     * where value inputs get ignored.
     *
     * @param int $hashMethod
     *
     * @return bool
     */
    public static function hasMaxLength($hashMethod)
    {
        return array_key_exists($hashMethod, self::MAX_LENGTHS);
    }

    /**
     * Find a decent default cost value for the server.
     * Taken from: http://php.net/manual/en/function.password-hash.php
     *
     * Ignore this static function since what it produces
     * will always depend upon the environment it is ran in.
     *
     * @codeCoverageIgnore
     *
     * @param float $targetTime
     *
     * @return int
     */
    public static function getAppropriateCostValue($targetTime = 0.05)
    {
        $cost = 8;
        do {
            $cost++;
            $start = microtime(true);
            password_hash('test', PASSWORD_DEFAULT, ['cost' => $cost]);
            $end = microtime(true);
        } while (($end - $start) < $targetTime);

        return $cost;
    }
}
