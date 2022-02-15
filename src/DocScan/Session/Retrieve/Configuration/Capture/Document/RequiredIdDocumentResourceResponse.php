<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture\Document;

class RequiredIdDocumentResourceResponse extends RequiredDocumentResourceResponse
{
    /**
     * @var SupportedCountryResponse[]|null
     */
    private $supportedCountries;

    /**
     * @var string|null
     */
    private $allowedCaptureMethods;

    /**
     * @var array<string, int>|null
     */
    private $attemptsRemaining;

    /**
     * @param array<string, mixed> $captureData
     */
    public function __construct(array $captureData)
    {
        parent::__construct($captureData);

        if (isset($captureData['requested_tasks'])) {
            foreach ($captureData['requested_tasks'] as $requestedTask) {
                $this->requestedTasks[] = $this->createTaskFromArray($requestedTask);
            }
        }

        if (isset($captureData['supported_countries'])) {
            foreach ($captureData['supported_countries'] as $supportedCountry) {
                $this->supportedCountries[] = new SupportedCountryResponse($supportedCountry);
            }
        }

        $this->allowedCaptureMethods = $captureData['allowed_capture_methods'] ?? null;
        $this->attemptsRemaining = $captureData['attempts_remaining'] ?? null;
    }

    /**
     * Returns a list of supported country codes, that can be used
     * to satisfy the requirement.  Each supported country will contain
     * a list of document types that can be used.
     *
     * @return SupportedCountryResponse[]|null
     */
    public function getSupportedCountries(): ?array
    {
        return $this->supportedCountries;
    }

    /**
     * Returns the allowed capture method as a String
     *
     * @return string|null
     */
    public function getAllowedCaptureMethods(): ?string
    {
        return $this->allowedCaptureMethods;
    }

    /**
     * Returns a Map, that is used to track how many attempts are
     * remaining when performing text-extraction.
     *
     * @return array<string, int>|null
     */
    public function getAttemptsRemaining(): ?array
    {
        return $this->attemptsRemaining;
    }
}
