<?php

namespace Yoti\DocScan\Session\Retrieve;

class FaceCaptureResourceResponse extends ResourceResponse
{
    /**
     * @var FaceCaptureImageResponse $image
     */
    private $image;

    /**
     * @return FaceCaptureImageResponse
     */
    public function getImage(): FaceCaptureImageResponse
    {
        return $this->image;
    }
}
