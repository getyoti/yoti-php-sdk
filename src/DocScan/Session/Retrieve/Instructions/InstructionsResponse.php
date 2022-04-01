<?php

namespace Yoti\DocScan\Session\Retrieve\Instructions;

use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Retrieve\Instructions\Branch\BranchResponse;
use Yoti\DocScan\Session\Retrieve\Instructions\Branch\UkPostOfficeBranchResponse;
use Yoti\DocScan\Session\Retrieve\Instructions\Branch\UnknownBranchResponse;
use Yoti\DocScan\Session\Retrieve\Instructions\Document\DocumentProposalResponse;

class InstructionsResponse
{
    /**
     * @var bool
     */
    private $contactProfileExists;

    /**
     * @var DocumentProposalResponse[]|null
     */
    private $documents;

    /**
     * @var BranchResponse|null
     */
    private $branch;

    /**
     * @param array<string, mixed> $sessionData
     */
    public function __construct(array $sessionData)
    {
        $this->contactProfileExists = $sessionData['contact_profile_exists'] ?? false;

        if (isset($sessionData['documents'])) {
            foreach ($sessionData['documents'] as $document) {
                $this->documents[] = new DocumentProposalResponse($document);
            }
        }

        if (isset($sessionData['branch'])) {
            switch ($sessionData['branch']['type']) {
                case Constants::UK_POST_OFFICE:
                    $this->branch = new UkPostOfficeBranchResponse($sessionData['branch']);
                    break;
                default:
                    $this->branch = new UnknownBranchResponse();
            }
        }
    }

    /**
     * @return bool
     */
    public function isContactProfileExists(): bool
    {
        return $this->contactProfileExists;
    }

    /**
     * @return DocumentProposalResponse[]|null
     */
    public function getDocuments(): ?array
    {
        return $this->documents;
    }

    /**
     * @return BranchResponse|null
     */
    public function getBranch(): ?BranchResponse
    {
        return $this->branch;
    }
}
