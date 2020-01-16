<?php

declare(strict_types=1);

namespace YotiSandbox\Http;

class SandboxPathManager
{
    /** @var string */
    private $tokenApiPath;

    public function __construct(string $tokenApiPath)
    {
        $this->tokenApiPath = $tokenApiPath;
    }

    public function getTokenApiPath(): string
    {
        return $this->tokenApiPath;
    }
}
