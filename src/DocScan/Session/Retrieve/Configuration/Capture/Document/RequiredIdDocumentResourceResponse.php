<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture\Document;

class RequiredIdDocumentResourceResponse extends RequiredDocumentResourceResponse
{
    /**
     * @var array<int, SupportedCountryResponse>
     */
    private $supportedCountries;

    /**
     * @var string
     */
    private $allowedCaptureMethods;

    /**
     * @var array<string, int>
     */
    private $attemptsRemaining;

    /**
     * Returns a list of supported country codes, that can be used
     * to satisfy the requirement.  Each supported country will contain
     * a list of document types that can be used.
     *
     * @return SupportedCountryResponse[]
     */
    public function getSupportedCountries(): array
    {
        return $this->supportedCountries;
    }

    /**
     * Returns the allowed capture method as a String
     *
     * @return string
     */
    public function getAllowedCaptureMethods(): string
    {
        return $this->allowedCaptureMethods;
    }

    /**
     * Returns a Map, that is used to track how many attempts are
     * remaining when performing text-extraction.
     *
     * @return int[]
     */
    public function getAttemptsRemaining(): array
    {
        return $this->attemptsRemaining;
    }
}
