<?php

namespace Yoti\DocScan\Session\Retrieve;

class FaceMapResponse
{

    /**
     * @var MediaResponse|null
     */
    private $media;

    /**
     * FacemapResponse constructor.
     * @param array<string, mixed> $facemap
     */
    public function __construct(array $facemap)
    {
        $this->media = isset($facemap['media'])
            ? new MediaResponse($facemap['media'])
            : null;
    }

    /**
     * @return MediaResponse|null
     */
    public function getMedia(): ?MediaResponse
    {
        return $this->media;
    }
}
