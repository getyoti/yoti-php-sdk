<?php

declare(strict_types=1);

namespace Yoti\DocScan\Support;

class SupportedDocument
{
    /**
     * @var string|null
     */
    private $type;

    /**
     * @param array<string, mixed> $document
     */
    public function __construct(array $document)
    {
        $this->type = $document['type'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }
}
