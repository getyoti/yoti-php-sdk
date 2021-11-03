<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture;

use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\AllowedSourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\RelyingBusinessAllowedSourceResponse;

abstract class RequiredResourceResponse
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $state;

    /**
     * @var array<int,AllowedSourceResponse>
     */
    private $allowedSources;

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
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @return AllowedSourceResponse[]
     */
    public function getAllowedSources(): array
    {
        return $this->allowedSources;
    }

    /**
     *
     * Returns if the Relying Business is allowed to upload resources
     * to satisfy the requirement.
     *
     * return the end user is allowed to upload resources
     *
     * @return bool
     */
    public function isRelyingBusinessAllowed(): bool
    {
        if (($this->allowedSources != null)) {
            foreach ($this->allowedSources as $allowedSource) {
                if ($allowedSource instanceof RelyingBusinessAllowedSourceResponse) {
                    return true;
                }
            }
        }

        return false;
    }
}
