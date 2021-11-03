<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture\Liveness;

use Yoti\DocScan\Session\Retrieve\Configuration\Capture\RequiredResourceResponse;

abstract class RequiredLivenessResourceResponse extends RequiredResourceResponse
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $livenessType;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getLivenessType(): string
    {
        return $this->livenessType;
    }
}
