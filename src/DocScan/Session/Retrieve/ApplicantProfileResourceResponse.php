<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

use Yoti\Exception\DateTimeException;
use Yoti\Util\DateTime;

class ApplicantProfileResourceResponse extends ResourceResponse
{
    /**
     * @var MediaResponse|null
     */
    private $media;

    /**
     * @var \DateTime|null
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     */
    private $lastUpdated;

    /**
     * ApplicantProfileResourceResponse constructor.
     * @param array<string, mixed> $applicantProfile
     * @throws DateTimeException
     */
    public function __construct(array $applicantProfile)
    {
        parent::__construct($applicantProfile);

        if (isset($applicantProfile['media'])) {
            $this->media = new MediaResponse($applicantProfile['media']);
        }

        $this->createdAt = isset($applicantProfile['created_at'])
            ? DateTime::stringToDateTime($applicantProfile['created_at']) : null;

        $this->lastUpdated = isset($applicantProfile['last_updated'])
            ? DateTime::stringToDateTime($applicantProfile['last_updated']) : null;
    }

    /**
     * @return MediaResponse|null
     */
    public function getMedia(): ?MediaResponse
    {
        return $this->media;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastUpdated(): ?\DateTime
    {
        return $this->lastUpdated;
    }
}
