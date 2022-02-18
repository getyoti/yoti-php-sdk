<?php

namespace Yoti\DocScan\Session\Retrieve\Instructions\Document;

use Yoti\DocScan\Constants;

class DocumentProposalResponse
{
    /**
     * @var string|null
     */
    private $requirementId;

    /**
     * @var SelectedDocumentResponse|null
     */
    private $document;

    /**
     * @param array<string, mixed> $documentData
     */
    public function __construct(array $documentData)
    {
        $this->requirementId = $documentData['requirement_id'];
        switch ($documentData['document']['type']) {
            case Constants::ID_DOCUMENT:
                $this->document = new SelectedIdDocumentResponse($documentData['document']);
                break;
            case Constants::SUPPLEMENTARY_DOCUMENT:
                $this->document = new SelectedSupplementaryDocumentResponse($documentData['document']);
                break;
            default:
                $this->document = new UnknownDocumentResponse($documentData['document']);
        }
    }

    /**
     * @return string|null
     */
    public function getRequirementId(): ?string
    {
        return $this->requirementId;
    }

    /**
     * @return SelectedDocumentResponse|null
     */
    public function getDocument(): ?SelectedDocumentResponse
    {
        return $this->document;
    }
}
