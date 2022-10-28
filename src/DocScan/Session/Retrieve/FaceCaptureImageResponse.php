<?php

namespace Yoti\DocScan\Session\Retrieve;

use Yoti\Exception\DateTimeException;

class FaceCaptureImageResponse
{
    /**
     * @var null|MediaResponse $media
     */
    private $media;

    /**
     * @param array<string,mixed> $image
     * @throws DateTimeException
     */
    public function __construct(array $image)
    {
        if (isset($image['media'])) {
            $this->media = new MediaResponse($image['media']);
        }
    }

    /**
     * @return null|MediaResponse
     */
    public function getMedia(): ?MediaResponse
    {
        return $this->media;
    }
}
