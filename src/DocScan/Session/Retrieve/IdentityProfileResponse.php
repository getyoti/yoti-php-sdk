<?php

namespace Yoti\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\IdentityProfile\FailureReasonResponse;

class IdentityProfileResponse
{
    /**
     * @var string
     */
    private $subjectId;

    /**
     * @var string
     */
    private $result;

    /**
     * @var FailureReasonResponse
     */
    private $failureReason;

    /**
     * @var object
     */
    private $identityProfileReport;

    /**
     * @param array<string, mixed> $sessionData
     */
    public function __construct(array $sessionData)
    {
        $this->subjectId = $sessionData['subject_id'];
        $this->result = $sessionData['result'];

        if (isset($sessionData['failure_reason'])) {
            $this->failureReason = new FailureReasonResponse($sessionData['failure_reason']['reason_code']);
        }

        if (isset($sessionData['identity_profile_report'])) {
            $this->identityProfileReport = (object)$sessionData['identity_profile_report'];
        }
    }

    /**
     * @return string
     */
    public function getSubjectId(): string
    {
        return $this->subjectId;
    }

    /**
     * @return string
     */
    public function getResult(): string
    {
        return $this->result;
    }

    /**
     * @return FailureReasonResponse
     */
    public function getFailureReason(): FailureReasonResponse
    {
        return $this->failureReason;
    }

    /**
     * @return object
     */
    public function getIdentityProfileReport()
    {
        return $this->identityProfileReport;
    }
}
