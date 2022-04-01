<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

use Yoti\Exception\DateTimeException;
use Yoti\Util\DateTime;

class CheckResponse
{
    /**
     * @var string|null
     */
    private $type;

    /**
     * @var string|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $state;

    /**
     * @var ReportResponse|null
     */
    private $report;

    /**
     * @var \DateTime|null
     */
    private $created;

    /**
     * @var \DateTime|null
     */
    private $lastUpdated;

    /**
     * @var string[]
     */
    private $resourcesUsed = [];

    /**
     * @var GeneratedMedia[]
     */
    private $generatedMedia = [];

    /**
     * CheckResponse constructor.
     * @param array<string, mixed> $checkData
     * @throws DateTimeException
     */
    public function __construct(array $checkData)
    {
        $this->type = $checkData['type'] ?? null;
        $this->id = $checkData['id'] ?? null;
        $this->state = $checkData['state'] ?? null;
        $this->resourcesUsed = $checkData['resources_used'] ?? [];

        if (isset($checkData['generated_media'])) {
            foreach ($checkData['generated_media'] as $generatedMedia) {
                $this->generatedMedia[] = new GeneratedMedia($generatedMedia);
            }
        }

        if (isset($checkData['report'])) {
            $this->report = new ReportResponse($checkData['report']);
        }

        $this->created = isset($checkData['created']) ?
            DateTime::stringToDateTime($checkData['created']) : null;

        $this->lastUpdated = isset($checkData['last_updated']) ?
            DateTime::stringToDateTime($checkData['last_updated']) : null;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
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
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @return ReportResponse|null
     */
    public function getReport(): ?ReportResponse
    {
        return $this->report;
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

    /**
     * @return string[]
     */
    public function getResourcesUsed(): array
    {
        return $this->resourcesUsed;
    }

    /**
     * @return GeneratedMedia[]
     */
    public function getGeneratedMedia(): array
    {
        return $this->generatedMedia;
    }
}
