<?php

namespace DevToolsGuru\Password;

class Info
{
    /** @var int $algorithm */
    private $algorithm;

    /** @var string $algorithmName */
    private $algorithmName;

    /** @var int $cost */
    private $cost;

    /**
     * Info constructor.
     *
     * @param string $hash
     */
    public function __construct($hash)
    {
        $info = password_get_info($hash);
        $this->algorithm = $info['algo'];
        $this->algorithmName = $info['algoName'];
        $this->cost = isset($info['options']['cost']) ?
            $info['options']['cost'] : 10;
    }

    /**
     * @return int
     */
    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    /**
     * @return string
     */
    public function getAlgorithmName()
    {
        return $this->algorithmName;
    }

    /**
     * @return int
     */
    public function getCost()
    {
        return $this->cost;
    }
}
