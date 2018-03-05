<?php

namespace Yoti\Util\Age;

abstract class AbstractAgeProcessor
{
    public $profileData;

    public function __construct(array $profileData)
    {
        $this->profileData = $profileData;
    }

    abstract public function process();
}