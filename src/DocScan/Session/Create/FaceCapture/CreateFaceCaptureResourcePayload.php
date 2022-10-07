<?php

namespace Yoti\DocScan\Session\Create\FaceCapture;

use JsonSerializable;
use stdClass;
use Yoti\Util\Json;

class CreateFaceCaptureResourcePayload implements JsonSerializable
{
    /**
     * @var string
     */
    public $requirementId;

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

    public function jsonSerialize(): stdClass
    {
        return (object)Json::withoutNullValues([
            'requirement_id' => $this->getRequirementId(),
        ]);
    }
}
