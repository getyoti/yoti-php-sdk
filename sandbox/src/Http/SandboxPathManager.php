<?php

namespace YotiSandbox\Http;

class SandboxPathManager
{
    private $tokenApiPath;

    public function __construct($tokenApiPath)
    {
        $this->tokenApiPath = $tokenApiPath;
    }

    public function getTokenApiPath()
    {
        return $this->tokenApiPath;
    }
}
