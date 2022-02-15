<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture\Document;

class SupportedDocumentResponse
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
     * @param array<string, mixed> $supportedDocument
     */
    public function __construct(array $supportedDocument)
    {
        $this->type = $supportedDocument['type'] ?? null;
        $this->isStrictlyLatin = $supportedDocument['is_strictly_latin'] ?? null;
    }

    /**
     * Returns the type of document that is supported.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return bool|null
     */
    public function isStrictlyLatin(): ?bool
    {
        return $this->isStrictlyLatin;
    }
}
