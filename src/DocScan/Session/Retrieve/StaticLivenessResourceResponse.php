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
     * The capture type of the static liveness resource.
     *
     * This is a string specific to STATIC liveness resources and may be null or
     * absent for older sessions. It is populated when the Relying Business
     * fetches the session.
     *
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
     * The capture type of the static liveness resource.
     *
     * This is specific to STATIC liveness resources and may be null when the
     * value is absent (e.g. for older sessions). It is populated when the
     * Relying Business fetches the session.
     *
     * @return string|null
     */
    public function getCaptureType(): ?string
    {
        return $this->captureType;
    }
}
