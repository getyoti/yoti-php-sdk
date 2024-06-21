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
        $this->requirementNotMetDetails = new RequirementNotMetDetails($data);
    }

    /**
     * @return RequirementNotMetDetails
     */
    public function getRequirementNotMetDetails(): RequirementNotMetDetails
    {
        return $this->requirementNotMetDetails;
    }
}
