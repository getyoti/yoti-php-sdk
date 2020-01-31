<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

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

    public function __construct(string $livenessType, int $maxRetries)
    {
        $this->livenessType = $livenessType;
        $this->maxRetries = $maxRetries;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'liveness_type' => $this->getLivenessType(),
            'max_retries' => $this->getMaxRetries(),
        ];
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
}
