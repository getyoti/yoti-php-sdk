<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture\Document;

class RequiredSupplementaryDocumentResourceResponse extends RequiredDocumentResourceResponse
{
    /**
     * @var array<int, string>
     */
    private $documentTypes;

    /**
     * @var array<int, string>
     */
    private $countryCodes;

    /**
     * @var ObjectiveResponse
     */
    private $objective;

    /**
     * Returns a list of document types that can be used to satisfy the requirement
     *
     * @return string[]
     */
    public function getDocumentTypes(): array
    {
        return $this->documentTypes;
    }

    /**
     * Returns a list of country codes that can be used to satisfy the requirement
     *
     * @return string[]
     */
    public function getCountryCodes(): array
    {
        return $this->countryCodes;
    }

    /**
     * Returns the objective that the {@link RequiredSupplementaryDocumentResourceResponse} will satisfy
     *
     * @return ObjectiveResponse
     */
    public function getObjective(): ObjectiveResponse
    {
        return $this->objective;
    }
}
