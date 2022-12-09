<?php

namespace Yoti\DocScan\Session\Retrieve;

use Yoti\Exception\DateTimeException;

class FaceCaptureResourceResponse extends ResourceResponse
{
    /**
     * @var FaceCaptureImageResponse $image
     */
    private $image;

    /**
     * FaceCaptureResourceResponse constructor.
     * @param array<string, mixed> $faceCaptures
     * @throws DateTimeException
     */
    public function __construct(array $faceCaptures)
    {
        parent::__construct($faceCaptures);

        if (isset($faceCaptures['image'])) {
            $this->image = new FaceCaptureImageResponse($faceCaptures['image']);
        }
    }

    /**
     * @return FaceCaptureImageResponse
     */
    public function getImage(): FaceCaptureImageResponse
    {
        return $this->image;
    }
}
