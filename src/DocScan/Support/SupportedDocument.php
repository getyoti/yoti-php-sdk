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
     * @var bool|null
     */
    private $isStrictlyLatin;

    /**
     * @param array<string, mixed> $document
     */
    public function __construct(array $document)
    {
        $this->type = $document['type'] ?? null;
        $this->isStrictlyLatin = $document['is_strictly_latin'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return bool|null
     */
    public function getIsStrictlyLatin(): ?bool
    {
        return $this->isStrictlyLatin;
    }
}
