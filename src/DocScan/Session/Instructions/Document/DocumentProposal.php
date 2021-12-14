<?php

namespace Yoti\DocScan\Session\Instructions\Document;

class DocumentProposal
{
    /**
     * @var string
     */
    private $requirementId;

    /**
     * @var SelectedDocument
     */
    private $document;

    /**
     * @param string $requirementId
     * @param SelectedDocument $document
     */
    public function __construct(string $requirementId, SelectedDocument $document)
    {
        $this->requirementId = $requirementId;
        $this->document = $document;
    }

    /**
     * @return string
     */
    public function getRequirementId(): string
    {
        return $this->requirementId;
    }

    /**
     * @return SelectedDocument
     */
    public function getDocument(): SelectedDocument
    {
        return $this->document;
    }
}
