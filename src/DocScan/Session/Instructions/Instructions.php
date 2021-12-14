<?php

namespace Yoti\DocScan\Session\Instructions;

use Yoti\DocScan\Session\Instructions\Branch\Branch;
use Yoti\DocScan\Session\Instructions\Document\DocumentProposal;

/**
 * Configures the instructions in order to be able to complete In-Branch Verification.
 * <p>
 * In order to provide the end-user with the instructions to complete an In-Branch Verification session,
 * you can supply the information necessary using the {@link Instructions} payload.
 */
class Instructions
{
    /**
     * @var ContactProfile
     */
    private $contactProfile;

    /**
     * @var DocumentProposal[]
     */
    private $documents;

    /**
     * @var Branch
     */
    private $branch;

    /**
     * @param ContactProfile $contactProfile
     * @param DocumentProposal[] $documents
     * @param Branch $branch
     */
    public function __construct(ContactProfile $contactProfile, array $documents, Branch $branch)
    {
        $this->contactProfile = $contactProfile;
        $this->documents = $documents;
        $this->branch = $branch;
    }

    /**
     * The contact profile that will be used for any communication
     * with the end-user
     *
     * @return ContactProfile
     */
    public function getContactProfile(): ContactProfile
    {
        return $this->contactProfile;
    }

    /**
     * A list of document proposals that will satisfy the sessions requirements.
     *
     * @return DocumentProposal[]
     */
    public function getDocuments(): array
    {
        return $this->documents;
    }

    /**
     * The branch that has been selected to perform the In-Branch Verification
     *
     * @return Branch
     */
    public function getBranch(): Branch
    {
        return $this->branch;
    }
}
