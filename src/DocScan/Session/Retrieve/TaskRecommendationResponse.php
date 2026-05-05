<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

class TaskRecommendationResponse
{
    /**
     * @var string|null
     */
    private $value;

    /**
     * @var TaskRecommendationReasonResponse|null
     */
    private $reason;

    /**
     * TaskRecommendationResponse constructor.
     * @param array<string, mixed> $recommendation
     */
    public function __construct(array $recommendation)
    {
        $this->value = $recommendation['value'] ?? null;

        if (isset($recommendation['reason'])) {
            $this->reason = new TaskRecommendationReasonResponse($recommendation['reason']);
        }
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @return TaskRecommendationReasonResponse|null
     */
    public function getReason(): ?TaskRecommendationReasonResponse
    {
        return $this->reason;
    }
}
