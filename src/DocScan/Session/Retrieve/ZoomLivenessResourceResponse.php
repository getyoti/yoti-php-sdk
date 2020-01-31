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

        $this->faceMap = isset($zoomLiveness['facemap'])
            ? new FaceMapResponse($zoomLiveness['facemap'])
            : null;

        if (isset($zoomLiveness['frames'])) {
            $this->frames = $this->parseFrames($zoomLiveness['frames']);
        }
    }

    /**
     * @param array<array<string, mixed>> $frames
     * @return FrameResponse[]
     */
    private function parseFrames(array $frames): array
    {
        $parsedFrames = [];
        foreach ($frames as $frame) {
            $parsedFrames[] = new FrameResponse($frame);
        }
        return $parsedFrames;
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
