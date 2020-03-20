<?php

namespace Yoti\DocScan\Session\Retrieve;

use Yoti\Exception\DateTimeException;
use Yoti\Util\DateTime;

class MediaResponse
{

    /**
     * @var string|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var \DateTime|null
     */
    private $created;

    /**
     * @var \DateTime|null
     */
    private $lastUpdated;

    /**
     * MediaResponse constructor.
     * @param array<string, mixed> $media
     * @throws DateTimeException
     */
    public function __construct(array $media)
    {
        $this->id = $media['id'] ?? null;
        $this->type = $media['type'] ?? null;
        $this->created = isset($media['created']) ? DateTime::stringToDateTime($media['created']) : null;
        $this->lastUpdated = isset($media['last_updated']) ? DateTime::stringToDateTime($media['last_updated']) : null;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastUpdated(): ?\DateTime
    {
        return $this->lastUpdated;
    }
}
