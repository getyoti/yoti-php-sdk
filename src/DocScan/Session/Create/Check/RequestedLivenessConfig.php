<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

use stdClass;

class RequestedLivenessConfig implements RequestedCheckConfigInterface
{
    /**
     * @var string
     */
    private $livenessType;

    /**
     * @var int
     */
    private $maxRetries;

    /**
     * @var string|null
     */
    private $manualCheck;

    public function __construct(string $livenessType, int $maxRetries, string $manualCheck = null)
    {
        $this->livenessType = $livenessType;
        $this->maxRetries = $maxRetries;
        $this->manualCheck = $manualCheck;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize(): stdClass
    {
        $data = [
            'liveness_type' => $this->getLivenessType(),
            'max_retries' => $this->getMaxRetries(),
        ];

        if (null !== $this->manualCheck) {
            $data['manual_check'] = $this->getManualCheck();
        }

        return (object)$data;
    }

    /**
     * @return string
     */
    public function getLivenessType(): string
    {
        return $this->livenessType;
    }

    /**
     * @return int
     */
    public function getMaxRetries(): int
    {
        return $this->maxRetries;
    }

    /**
     * @return string|null
     */
    public function getManualCheck(): ?string
    {
        return $this->manualCheck;
    }
}
