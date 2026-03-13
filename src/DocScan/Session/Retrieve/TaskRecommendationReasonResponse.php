<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

class TaskRecommendationReasonResponse
{
    /**
     * @var string|null
     */
    private $value;

    /**
     * @var string|null
     */
    private $detail;

    /**
     * TaskRecommendationReasonResponse constructor.
     * @param array<string, mixed> $reason
     */
    public function __construct(array $reason)
    {
        $this->value = $reason['value'] ?? null;
        $this->detail = $reason['detail'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @return string|null
     */
    public function getDetail(): ?string
    {
        return $this->detail;
    }
}
