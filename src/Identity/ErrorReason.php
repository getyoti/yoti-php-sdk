<?php

namespace Yoti\Identity;

class ErrorReason
{
    /**
     * @var RequirementNotMetDetails
     */
    private $requirementNotMetDetails;


    /**
     * @param RequirementNotMetDetails $data
     */
    public function __construct(RequirementNotMetDetails $data)
    {
        $this->requirementNotMetDetails = $data;
    }

    /**
     * @return RequirementNotMetDetails
     */
    public function getRequirementNotMetDetails(): RequirementNotMetDetails
    {
        return $this->requirementNotMetDetails;
    }
}
