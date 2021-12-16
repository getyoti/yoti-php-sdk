<?php

namespace Yoti\DocScan\Session\Instructions\Document;

class DocumentProposal
{
    /**
     * @var string|null
     */
    private $requirementId;

    /**
     * @var SelectedDocument|null
     */
    private $document;

    /**
     * @param string|null $requirementId
     * @param SelectedDocument|null $document
     */
    public function __construct(?string $requirementId, ?SelectedDocument $document)
    {
        $this->requirementId = $requirementId;
        $this->document = $document;
    }

    /**
     * @return string|null
     */
    public function getRequirementId(): ?string
    {
        return $this->requirementId;
    }

    /**
     * @return SelectedDocument|null
     */
    public function getDocument(): ?SelectedDocument
    {
        return $this->document;
    }
}
