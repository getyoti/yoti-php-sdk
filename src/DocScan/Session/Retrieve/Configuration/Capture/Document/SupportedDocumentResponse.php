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
     * @var string[]|null
     */
    private $providers;

    /**
     * @param array<string, mixed> $supportedDocument
     */
    public function __construct(array $supportedDocument)
    {
        $this->type = $supportedDocument['type'] ?? null;
        $this->isStrictlyLatin = $supportedDocument['is_strictly_latin'] ?? null;
        $this->providers = $supportedDocument['providers'] ?? null;
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

    /**
     * Returns the list of digital ID providers that support this document type,
     * if any.
     *
     * @return string[]|null
     */
    public function getProviders(): ?array
    {
        return $this->providers;
    }
}
