<?php

namespace Yoti\DocScan\Session\Retrieve\IdentityProfile;

class FailureReasonResponse
{
    /**
     * @var string
     */
    private $stringCode;

    /**
     * @param string $stringCode
     */
    public function __construct(string $stringCode)
    {
        $this->stringCode = $stringCode;
    }

    /**
     * @return string
     */
    public function getStringCode(): string
    {
        return $this->stringCode;
    }
}
