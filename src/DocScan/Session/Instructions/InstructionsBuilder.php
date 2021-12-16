<?php

namespace Yoti\DocScan\Session\Instructions;

use Yoti\DocScan\Session\Instructions\Branch\Branch;
use Yoti\DocScan\Session\Instructions\Document\DocumentProposal;

class InstructionsBuilder
{
    /**
     * @var ContactProfile|null
     */
    private $contactProfile;

    /**
     * @var DocumentProposal[]|null
     */
    private $documents;

    /**
     * @var Branch|null
     */
    private $branch;

    /**
     * Sets the contact profile that will be used for any communication
     * with the end-user
     *
     * @param ContactProfile $contactProfile
     * @return $this
     */
    public function withContactProfile(ContactProfile $contactProfile): InstructionsBuilder
    {
        $this->contactProfile = $contactProfile;
        return $this;
    }

    /**
     *  Adds a singular {@link DocumentProposal} to a list of documents that the
     * end-user will need to provide when performing In-Branch Verification
     *
     * @param DocumentProposal $document
     * @return $this
     */
    public function withDocumentProposal(DocumentProposal $document): InstructionsBuilder
    {
        $this->documents[] = $document;
        return $this;
    }

    /**
     * Sets the branch that the end-user will perform the In-Branch Verification
     *
     * @param Branch $branch
     * @return $this
     */
    public function withBranch(Branch $branch): InstructionsBuilder
    {
        $this->branch = $branch;
        return $this;
    }

    /**
     * @return Instructions
     */
    public function build(): Instructions
    {
        return new Instructions($this->contactProfile, $this->documents, $this->branch);
    }
}
