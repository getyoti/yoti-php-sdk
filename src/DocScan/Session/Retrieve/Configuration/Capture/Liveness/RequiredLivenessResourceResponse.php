<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture\Liveness;

use Yoti\DocScan\Session\Retrieve\Configuration\Capture\RequiredResourceResponse;

abstract class RequiredLivenessResourceResponse extends RequiredResourceResponse
{
    /**
     * @var string|null
     */
    private $livenessType;

    /**
     * @param array<string, string> $captureData
     */
    public function __construct(array $captureData)
    {
        parent::__construct($captureData);

        $this->livenessType = $captureData['liveness_type'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getLivenessType(): ?string
    {
        return $this->livenessType;
    }
}
