<?php

namespace Yoti\DocScan\Session\Instructions\Document;

class DocumentProposalBuilder
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
     * Sets the requirementId that the document proposal will satisfy
     *
     * @param string $requirementId
     * @return $this
     */
    public function withRequirementId(string $requirementId): DocumentProposalBuilder
    {
        $this->requirementId = $requirementId;
        return $this;
    }

    /**
     * Sets the {@link SelectedDocument} that will be used to satisfy the requirement
     *
     * @param SelectedDocument $document
     * @return $this
     */
    public function withSelectedDocument(SelectedDocument $document): DocumentProposalBuilder
    {
        $this->document = $document;
        return $this;
    }

    /**
     * @return DocumentProposal
     */
    public function build(): DocumentProposal
    {
        return new DocumentProposal($this->requirementId, $this->document);
    }
}
