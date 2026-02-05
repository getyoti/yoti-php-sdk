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
     * @var string|null
     */
    private $captureType;

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

        $this->captureType = $zoomLiveness['capture_type'] ?? null;
    }

    /**
     * @return MediaResponse|null
     */
    public function getImage(): ?MediaResponse
    {
        return $this->image;
    }

    /**
     * @return string|null
     */
    public function getCaptureType(): ?string
    {
        return $this->captureType;
    }
}
