<?php

namespace YotiSandbox\Http;

class SandboxPathManager
{
    /**
     * This path is for profile request
     */
    const DEFAULT_PROFILE_API_PATH = 'https://dev0.api.yoti.com/sandbox/v1';
    /**
     * This path is for creating a Sandbox Application and for a token request
     */
    const DEFAULT_TOKEN_API_PATH = 'https://dev0.api.yoti.com:11443/sandbox/v1';

    private $tokenApiPath;
    private $profileApiPath;

    public function __construct(
        $tokenApiPath = self::DEFAULT_TOKEN_API_PATH,
        $profileApiPath = self::DEFAULT_PROFILE_API_PATH
    )
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