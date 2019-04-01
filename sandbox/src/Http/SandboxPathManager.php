<?php

namespace YotiSandbox\Http;

class SandboxPathManager
{
    private $tokenApiPath;
    private $profileApiPath;

    public function __construct($tokenApiPath, $profileApiPath)
    {
        $this->tokenApiPath = $tokenApiPath;
        $this->profileApiPath = $profileApiPath;
    }

    public function getTokenApiPath()
    {
        return $this->tokenApiPath;
    }

    public function getProfileApiPath()
    {
        return $this->profileApiPath;
    }
}