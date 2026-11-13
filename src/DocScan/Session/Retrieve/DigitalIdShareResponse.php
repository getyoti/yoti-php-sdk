<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

use Yoti\Util\DateTime;

class DigitalIdShareResponse
{
    /**
     * @var string|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $documentType;

    /**
     * @var string|null
     */
    private $issuingCountry;

    /**
     * @var string|null
     */
    private $provider;

    /**
     * @var \DateTime|null
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     */
    private $lastUpdated;

    /**
     * @var string|null
     */
    private $resourceId;

    /**
     * @var DigitalIdShareErrorResponse|null
     */
    private $error;

    /**
     * @param array<string, mixed> $digitalIdShare
     *
     * @throws \Yoti\Exception\DateTimeException
     */
    public function __construct(array $digitalIdShare)
    {
        $this->id = $digitalIdShare['id'] ?? null;
        $this->documentType = $digitalIdShare['document_type'] ?? null;
        $this->issuingCountry = $digitalIdShare['issuing_country'] ?? null;
        $this->provider = $digitalIdShare['provider'] ?? null;
        $this->resourceId = $digitalIdShare['resource_id'] ?? null;

        $this->createdAt = isset($digitalIdShare['created_at'])
            ? DateTime::stringToDateTime($digitalIdShare['created_at'])
            : null;

        $this->lastUpdated = isset($digitalIdShare['last_updated'])
            ? DateTime::stringToDateTime($digitalIdShare['last_updated'])
            : null;

        $this->error = isset($digitalIdShare['error'])
            ? new DigitalIdShareErrorResponse($digitalIdShare['error'])
            : null;
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
    public function getDocumentType(): ?string
    {
        return $this->documentType;
    }

    /**
     * @return string|null
     */
    public function getIssuingCountry(): ?string
    {
        return $this->issuingCountry;
    }

    /**
     * @return string|null
     */
    public function getProvider(): ?string
    {
        return $this->provider;
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

    /**
     * @return string|null
     */
    public function getResourceId(): ?string
    {
        return $this->resourceId;
    }

    /**
     * @return DigitalIdShareErrorResponse|null
     */
    public function getError(): ?DigitalIdShareErrorResponse
    {
        return $this->error;
    }
}
