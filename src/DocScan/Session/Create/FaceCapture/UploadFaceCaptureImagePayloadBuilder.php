<?php

namespace Yoti\DocScan\Session\Create\FaceCapture;

use Yoti\Media\Image\Jpeg;
use Yoti\Media\Image\Png;

class UploadFaceCaptureImagePayloadBuilder
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
     * Sets the content type for uploading a JPEG image
     *
     * @return $this
     */
    public function forJpegImage(): UploadFaceCaptureImagePayloadBuilder
    {
        $this->imageContentType = Jpeg::MIME_TYPE;

        return $this;
    }

    /**
     * Sets the content type for uploading a PNG image
     *
     * @return $this
     */
    public function forPngImage(): UploadFaceCaptureImagePayloadBuilder
    {
        $this->imageContentType = Png::MIME_TYPE;

        return $this;
    }

    /**
     * Sets the contents of the image to be uploaded
     *
     * @param string $imageContents
     * @return $this
     */
    public function withImageContents(string $imageContents): UploadFaceCaptureImagePayloadBuilder
    {
        $this->imageContents = $imageContents;

        return $this;
    }

    /**
     * @return UploadFaceCaptureImagePayload
     */
    public function build(): UploadFaceCaptureImagePayload
    {
        return new UploadFaceCaptureImagePayload($this->imageContentType, $this->imageContents);
    }
}
