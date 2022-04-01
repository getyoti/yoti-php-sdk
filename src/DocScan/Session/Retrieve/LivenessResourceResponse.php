<?php

namespace Yoti\DocScan\Session\Retrieve;

class LivenessResourceResponse extends ResourceResponse
{
    /**
     * @var string|null
     */
    private $livenessType;

    /**
     * @param array<string, mixed> $resource
     */
    public function __construct(array $resource)
    {
        parent::__construct($resource);

        $this->livenessType = $resource['liveness_type'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getLivenessType(): ?string
    {
        return $this->livenessType;
    }
}
