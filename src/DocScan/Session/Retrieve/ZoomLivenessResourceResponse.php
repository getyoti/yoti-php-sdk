<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

class ZoomLivenessResourceResponse extends LivenessResourceResponse
{

    /**
     * @var FaceMapResponse|null
     */
    private $faceMap;

    /**
     * @var FrameResponse[]
     */
    private $frames = [];

    /**
     * ZoomLivenessResourceResponse constructor.
     * @param array<string, mixed> $zoomLiveness
     */
    public function __construct(array $zoomLiveness)
    {
        parent::__construct($zoomLiveness);

        if (isset($zoomLiveness['facemap'])) {
            $this->faceMap = new FaceMapResponse($zoomLiveness['facemap']);
        }

        if (isset($zoomLiveness['frames']) && is_array($zoomLiveness['frames'])) {
            foreach ($zoomLiveness['frames'] as $frame) {
                $this->frames[] = new FrameResponse($frame);
            }
        }
    }

    /**
     * @return FaceMapResponse|null
     */
    public function getFaceMap(): ?FaceMapResponse
    {
        return $this->faceMap;
    }

    /**
     * @return FrameResponse[]
     */
    public function getFrames(): array
    {
        return $this->frames;
    }
}
