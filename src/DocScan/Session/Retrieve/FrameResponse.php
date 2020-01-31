<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

class FrameResponse
{

    /**
     * @var MediaResponse|null
     */
    private $media;

    /**
     * FrameResponse constructor.
     * @param array<string, mixed> $frame
     */
    public function __construct(array $frame)
    {
        $this->media = isset($frame['media'])
            ? new MediaResponse($frame['media'])
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
