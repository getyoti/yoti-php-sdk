<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture\Document;

class ObjectiveResponse
{
    /**
     * @var string|null
     */
    private $type;

    /**
     * @param array<string, string> $objective
     */
    public function __construct(array $objective)
    {
        $this->type = $objective['type'] ?? null;
    }

    /**
     * Returns the objective type as a String
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }
}
