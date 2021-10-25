<?php

namespace Yoti\DocScan\Session\Create\FaceCapture;

class UploadFaceCaptureImagePayload
{
    /**
     * @var string
     */
    private $imageContentType;

    /**
     * @var array<string, mixed>
     */
    private $imageContents;

    /**
     * @param string $imageContentType
     * @param array<string, mixed> $imageContents
     */
    public function __construct(string $imageContentType, array $imageContents)
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
     * @return array<string, mixed>
     */
    public function getImageContents(): array
    {
        return $this->imageContents;
    }
}
