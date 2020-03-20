<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

class DocumentFieldsResponse
{

    /**
     * @var MediaResponse|null
     */
    private $media;

    /**
     * DocumentFieldsResponse constructor.
     * @param array<string, mixed> $documentFields
     */
    public function __construct(array $documentFields)
    {
        $this->media = isset($documentFields['media'])
            ? new MediaResponse($documentFields['media'])
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
