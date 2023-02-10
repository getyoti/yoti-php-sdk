<?php

namespace Yoti\DocScan\Session\Retrieve;

use Yoti\Exception\DateTimeException;

class ImportTokenResponse
{
    private ?MediaResponse $media;

    private ?string $failureReason;

    /**
     * @param array<string,mixed> $data
     *
     * @throws DateTimeException
     */
    public function __construct(array $data)
    {
        if (isset($data['media'])) {
            $this->media = new MediaResponse($data['media']);
        }
        if (isset($data['failure_reason'])) {
            $this->failureReason = $data['failure_reason'];
        }
    }

    /**
     * @return string|null
     */
    public function getFailureReason(): ?string
    {
        return $this->failureReason;
    }

    /**
     * @return MediaResponse|null
     */
    public function getMedia(): ?MediaResponse
    {
        return $this->media;
    }
}
