<?php

namespace DevToolsGuru\Password;

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
     * Password constructor.
     *
     * @param string $value
     * @param int $cost
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $value, int $cost = 10)
    {
        if (in_array(password_get_info($value)['algo'], array_values(self::AVAILABLE_ALGORITHMS), true)) {
            $this->setProps($value);
            return;
        }

        if (self::$errorOnExcessiveLength &&
            $this->hasMaxLength(PASSWORD_DEFAULT) &&
            strlen($value) >= self::MAX_LENGTHS[PASSWORD_DEFAULT]) {
            $exception = new ExcessiveLengthException();
            $exception->setMaxLength(self::MAX_LENGTHS[PASSWORD_DEFAULT]);
            throw $exception;
        }

        $this->setProps(password_hash($value, PASSWORD_DEFAULT, ['cost' => $cost]));
    }

    private function setProps(string $value)
    {
        $info = password_get_info($value);
        $this->hash = $value;
        $this->cost = $info['options']['cost'] ?? 10;
        $this->algorithm = $info['algo'];
    }

    public function getHash() : string
    {
        return $this->hash;
    }

    public function getCost() : int
    {
        return $this->cost;
    }

    public function getAlgorithm() : int
    {
        return $this->algorithm;
    }

    public function verify(string $plainText) : bool
    {
        return password_verify($plainText, $this->hash);
    }

    public function needsRehash(int $cost = 10) : bool
    {
        return password_needs_rehash($this->hash, PASSWORD_DEFAULT, ['cost' => $cost]);
    }

    public function hasMaxLength(int $hashMethod): bool
    {
        return array_key_exists($hashMethod, self::MAX_LENGTHS);
    }

    /**
     * Find a decent default cost value for the server.
     * Taken from: http://php.net/manual/en/function.password-hash.php
     *
     * Ignore this static function since what it produces
     * will always depend upon the environment it is ran in.
     * @codeCoverageIgnore
     *
     * @param float $targetTime
     *
     * @return int
     */
    public static function getAppropriateCostValue(float $targetTime = 0.05) : int
    {
        $cost = 8;
        do {
            $cost++;
            $start = microtime(true);
            password_hash("test", PASSWORD_DEFAULT, ["cost" => $cost]);
            $end = microtime(true);
        } while (($end - $start) < $targetTime);

        return $cost;
    }
}
