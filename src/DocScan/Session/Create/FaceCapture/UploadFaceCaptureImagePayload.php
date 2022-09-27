<?php

namespace Yoti\DocScan\Session\Create\FaceCapture;

class UploadFaceCaptureImagePayload
{
    /**
     * @var string
     */
    private $imageContentType;

    /**
     * @var string
     */
    private $imageContents;

    /**
     * @param string $imageContentType
     * @param string $imageContents
     */
    public function __construct(string $imageContentType, string $imageContents)
    {
        $this->imageContentType = $imageContentType;
        $this->imageContents = $imageContents;
    }

    /**
     * @return string
     */
    public function getImageContentType(): string
    {
        return $this->imageContentType;
    }

    /**
     * @return string
     */
    public function getImageContents(): string
    {
        return $this->imageContents;
    }
}
