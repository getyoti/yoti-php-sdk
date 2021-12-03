<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture\Document;

class SupportedDocumentResponse
{
    /**
     * @var string|null
     */
    private $type;

    /**
     * @param array<string, string> $supportedDocument
     */
    public function __construct(array $supportedDocument)
    {
        $this->type = $supportedDocument['type'] ?? null;
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
}
