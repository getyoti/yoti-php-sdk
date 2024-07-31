<?php

namespace Yoti\DocScan\Session\Retrieve\IdentityProfile;

class FailureReasonResponse
{
    /**
     * @var string
     */
    private $reasonCode;
    /**
     * @var RequirementNotMetDetails
     */
    private $requirementsNotMetDetails;
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        $this->reasonCode = $data["reason_code"];
        $this->requirementsNotMetDetails = new RequirementNotMetDetails($data["requirements_not_met_details"]);
    }

    /**
     * @return string
     */
    public function getReasonCode(): string
    {
        return $this->reasonCode;
    }
    /**
     * @return RequirementNotMetDetails
     */
    public function getRequirementNotMetDetails(): RequirementNotMetDetails
    {
        return $this->requirementsNotMetDetails;
    }
}
