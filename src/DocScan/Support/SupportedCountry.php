<?php

declare(strict_types=1);

namespace Yoti\DocScan\Support;

class SupportedCountry
{
    /**
     * @var string|null
     */
    private $code;

    /**
     * @var SupportedDocument[]
     */
    private $supportedDocuments = [];

    /**
     * @param array<string, mixed> $country
     */
    public function __construct($country)
    {
        $this->code = $country['code'] ?? null;

        if (isset($country['supported_documents'])) {
            $this->supportedDocuments = array_map(
                function ($document): SupportedDocument {
                    return new SupportedDocument($document);
                },
                $country['supported_documents']
            );
        }
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @return SupportedDocument[]
     */
    public function getSupportedDocuments(): array
    {
        return $this->supportedDocuments;
    }
}
