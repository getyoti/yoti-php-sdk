<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture;

use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\AllowedSourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\EndUserAllowedSourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\IbvAllowedSourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\RelyingBusinessAllowedSourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\UnknownAllowedSourceResponse;

class RequiredResourceResponse
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
     * @param array<string, mixed> $captureData
     */
    public function __construct(array $captureData)
    {
        $this->type = $captureData['type'] ?? null;
        $this->id = $captureData['id'] ?? null;
        $this->state = $captureData['state'] ?? null;

        if (isset($captureData['allowed_sources'])) {
            foreach ($captureData['allowed_sources'] as $allowedSource) {
                $this->allowedSources[] = $this->createAllowedSourceFromArray($allowedSource);
            }
        }
    }

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
        if ($this->allowedSources != null) {
            foreach ($this->allowedSources as $allowedSource) {
                if ($allowedSource instanceof RelyingBusinessAllowedSourceResponse) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param array<string, string> $source
     * @return AllowedSourceResponse
     */
    private function createAllowedSourceFromArray(array $source): AllowedSourceResponse
    {
        switch ($source['type'] ?? null) {
            case Constants::END_USER:
                return new EndUserAllowedSourceResponse();
            case Constants::IBV:
                return new IbvAllowedSourceResponse();
            case Constants::RELYING_BUSINESS:
                return new RelyingBusinessAllowedSourceResponse();
            default:
                return new UnknownAllowedSourceResponse();
        }
    }
}
