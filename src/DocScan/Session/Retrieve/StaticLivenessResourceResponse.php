<?php

namespace Yoti\DocScan\Session\Retrieve;

use Yoti\Exception\DateTimeException;

class StaticLivenessResourceResponse extends LivenessResourceResponse
{
    /**
     * @var MediaResponse|null
     */
    private $image;


    /**
     * StaticLivenessResourceResponse constructor.
     * @param array<string, mixed> $zoomLiveness
     * @throws DateTimeException
     */
    public function __construct(array $zoomLiveness)
    {
        parent::__construct($zoomLiveness);

        if (isset($zoomLiveness['image'])) {
            $this->image = new MediaResponse($zoomLiveness['image']['media']);
        }
    }

    /**
     * @return MediaResponse|null
     */
    public function getImage(): ?MediaResponse
    {
        return $this->image;
    }
}
