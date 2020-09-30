<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

class PageResponse
{

    /**
     * @var string|null
     */
    private $captureMethod;

    /**
     * @var MediaResponse|null
     */
    private $media;

    /**
     * @var FrameResponse[]
     */
    private $frames = [];

    /**
     * PageInfo constructor.
     * @param array<string, mixed> $page
     */
    public function __construct(array $page)
    {
        $this->captureMethod = $page['capture_method'] ?? null;

        if (isset($page['media'])) {
            $this->media = new MediaResponse($page['media']);
        }

        if (isset($page['frames']) && is_array($page['frames'])) {
            foreach ($page['frames'] as $frame) {
                $this->frames[] = new FrameResponse($frame);
            }
        }
    }

    /**
     * @return string|null
     */
    public function getCaptureMethod(): ?string
    {
        return $this->captureMethod;
    }

    /**
     * @return MediaResponse|null
     */
    public function getMedia(): ?MediaResponse
    {
        return $this->media;
    }

    /**
     * @return FrameResponse[]
     */
    public function getFrames(): array
    {
        return $this->frames;
    }
}
