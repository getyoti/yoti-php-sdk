<?php

namespace Yoti\Identity;

class ErrorReason
{
    /**
     * @var RequirementNotMetDetails
     */
    private $requirementNotMetDetails;

    /**
     * @param array<int, array<string, string>> $data
     */
    public function __construct(array $data)
    {
        if (isset($data[0])) {
            $this->requirementNotMetDetails = new RequirementNotMetDetails($data);
        } else {
            $this->requirementNotMetDetails = new RequirementNotMetDetails([[
                "failure_type" => '',
                "details" => '',
                "audit_id" => '',
                "document_country_iso_code" => '',
                "document_type" => ''
            ]]);
        }
    }

    /**
     * @return RequirementNotMetDetails
     */
    public function getRequirementNotMetDetails(): RequirementNotMetDetails
    {
        return $this->requirementNotMetDetails;
    }
}
