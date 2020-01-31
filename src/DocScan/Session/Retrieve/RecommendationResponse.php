<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

class RecommendationResponse
{

    /**
     * @var string|null
     */
    private $value;

    /**
     * @var string|null
     */
    private $reason;

    /**
     * @var string|null
     */
    private $recoverySuggestion;

    /**
     * RecommendationResponse constructor.
     * @param array<string, mixed> $recommendation
     */
    public function __construct(array $recommendation)
    {
        $this->value = $recommendation['value'] ?? null;
        $this->reason = $recommendation['reason'] ?? null;
        $this->recoverySuggestion = $recommendation['recovery_suggestion'] ?? null;
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
    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * @return string|null
     */
    public function getRecoverySuggestion(): ?string
    {
        return $this->recoverySuggestion;
    }
}
