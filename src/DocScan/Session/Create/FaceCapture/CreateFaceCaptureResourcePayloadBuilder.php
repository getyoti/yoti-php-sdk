<?php

namespace Yoti\DocScan\Session\Create\FaceCapture;

class CreateFaceCaptureResourcePayloadBuilder
{
    /**
     * @var string
     */
    private $requirementId;

    /**
     * Sets the id of the requirement that the resource will be used to satisfy.
     *
     * @param string $requirementId
     * @return CreateFaceCaptureResourcePayloadBuilder
     */
    public function withRequirementId(string $requirementId): CreateFaceCaptureResourcePayloadBuilder
    {
        $this->requirementId = $requirementId;

        return $this;
    }

    /**
     * @return CreateFaceCaptureResourcePayload
     */
    public function build(): CreateFaceCaptureResourcePayload
    {
        return new CreateFaceCaptureResourcePayload($this->requirementId);
    }
}
