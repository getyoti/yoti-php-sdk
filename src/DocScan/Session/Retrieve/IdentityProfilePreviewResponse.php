<?php

namespace Yoti\DocScan\Session\Retrieve;

class IdentityProfilePreviewResponse
{
    private ?MediaResponse $media = null;

    /**
     * @param array<string, mixed> $sessionData
     * @throws \Yoti\Exception\DateTimeException
     */
    public function __construct(array $sessionData)
    {
        if (isset($sessionData['media'])) {
            $this->media = new MediaResponse($sessionData['media']);
        }
    }

    /**
     * @return MediaResponse|null
     */
    public function getMedia(): ?MediaResponse
    {
        return $this->media;
    }
}
