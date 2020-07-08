<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

class DocumentIdPhotoResponse
{

    /**
     * @var MediaResponse|null
     */
    private $media;

    /**
     * @param array<string, mixed> $documentIdPhoto
     */
    public function __construct(array $documentIdPhoto)
    {
        $this->media = isset($documentIdPhoto['media'])
            ? new MediaResponse($documentIdPhoto['media'])
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
