<?php

namespace Yoti\DocScan\Session\Create\FaceCapture;

class CreateFaceCaptureResourcePayload
{
    /**
     * @var string
     */
    private $requirementId;

    /**
     * @param string $requirementId
     */
    public function __construct(string $requirementId)
    {
        $this->requirementId = $requirementId;
    }

    /**
     * @return string
     */
    public function getRequirementId(): string
    {
        return $this->requirementId;
    }
}
