<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture\Document;

class RequiredSupplementaryDocumentResourceResponse extends RequiredDocumentResourceResponse
{
    /**
     * @var string[]|null
     */
    private $documentTypes;

    /**
     * @var string[]|null
     */
    private $countryCodes;

    /**
     * @var ObjectiveResponse|null
     */
    private $objective;

    /**
     * @param array<string, mixed> $captureData
     */
    public function __construct(array $captureData)
    {
        parent::__construct($captureData);

        $this->documentTypes = $captureData['document_types'] ?? null;
        $this->countryCodes = $captureData['country_codes'] ?? null;

        if (isset($captureData['requested_tasks'])) {
            foreach ($captureData['requested_tasks'] as $requestedTask) {
                $this->requestedTasks[] = $this->createTaskFromArray($requestedTask);
            }
        }

        if (isset($captureData['objective'])) {
            $this->objective = new ObjectiveResponse($captureData['objective']);
        }
    }

    /**
     * Returns a list of document types that can be used to satisfy the requirement
     *
     * @return string[]|null
     */
    public function getDocumentTypes(): ?array
    {
        return $this->documentTypes;
    }

    /**
     * Returns a list of country codes that can be used to satisfy the requirement
     *
     * @return string[]|null
     */
    public function getCountryCodes(): ?array
    {
        return $this->countryCodes;
    }

    /**
     * Returns the objective that the {@link RequiredSupplementaryDocumentResourceResponse} will satisfy
     *
     * @return ObjectiveResponse|null
     */
    public function getObjective(): ?ObjectiveResponse
    {
        return $this->objective;
    }
}
