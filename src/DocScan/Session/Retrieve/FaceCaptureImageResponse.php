<?php

namespace Yoti\DocScan\Session\Retrieve;

class FaceCaptureImageResponse
{
    /**
     * @var MediaResponse $media
     */
    private $media;

    /**
     * @return MediaResponse
     */
    public function getMedia(): MediaResponse
    {
        return $this->media;
    }
}
