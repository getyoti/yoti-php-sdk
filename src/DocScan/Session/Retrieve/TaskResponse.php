<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

use Yoti\DocScan\Constants;
use Yoti\Util\DateTime;

class TaskResponse
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
     * @var \DateTime|null
     */
    private $created;

    /**
     * @var \DateTime|null
     */
    private $lastUpdated;

    /**
     * @var GeneratedCheckResponse[]
     */
    private $generatedChecks = [];

    /**
     * @var GeneratedMedia[]
     */
    private $generatedMedia = [];

    /**
     * TaskResponse constructor.
     * @param array<string, mixed> $task
     */
    public function __construct(array $task)
    {
        $this->type = $task['type'] ?? null;
        $this->id = $task['id'] ?? null;
        $this->state = $task['state'] ?? null;

        $this->created = isset($task['created']) ?
            DateTime::stringToDateTime($task['created']) : null;

        $this->lastUpdated = isset($task['last_updated']) ?
            DateTime::stringToDateTime($task['last_updated']) : null;

        if (isset($task['generated_checks'])) {
            $this->generatedChecks = $this->parseGeneratedChecks($task['generated_checks']);
        }

        if (isset($task['generated_media'])) {
            $this->generatedMedia = $this->parseGeneratedMedia($task['generated_media']);
        }
    }

    /**
     * @param array<array<string, mixed>> $generatedChecks
     * @return GeneratedCheckResponse[]
     */
    private function parseGeneratedChecks(array $generatedChecks): array
    {
        $parsedGeneratedChecks = [];
        foreach ($generatedChecks as $generatedCheck) {
            switch ($generatedCheck['type'] ?? null) {
                case Constants::ID_DOCUMENT_TEXT_DATA_CHECK:
                    $parsedGeneratedChecks[] = new GeneratedTextDataCheckResponse($generatedCheck);
                    break;
                case Constants::SUPPLEMENTARY_DOCUMENT_TEXT_DATA_CHECK:
                    $parsedGeneratedChecks[] = new GeneratedSupplementaryDocTextDataCheckResponse($generatedCheck);
                    break;
                default:
                    $parsedGeneratedChecks[] = new GeneratedCheckResponse($generatedCheck);
                    break;
            }
        }
        return $parsedGeneratedChecks;
    }

    /**
     * @param array<array<string, mixed>> $generatedMediaArray
     * @return GeneratedMedia[]
     */
    private function parseGeneratedMedia(array $generatedMediaArray): array
    {
        $parsedGeneratedMedia = [];
        foreach ($generatedMediaArray as $generatedMedia) {
            $parsedGeneratedMedia[] = new GeneratedMedia($generatedMedia);
        }
        return $parsedGeneratedMedia;
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
     * @return GeneratedCheckResponse[]
     */
    public function getGeneratedChecks(): array
    {
        return $this->generatedChecks;
    }

    /**
     * @return GeneratedMedia[]
     */
    public function getGeneratedMedia(): array
    {
        return $this->generatedMedia;
    }

    /**
     * @return GeneratedCheckResponse[]
     */
    public function getGeneratedTextDataChecks(): array
    {
        return $this->filterGeneratedChecksByType(GeneratedTextDataCheckResponse::class);
    }

    /**
     * @param string $class
     * @return mixed[]
     */
    protected function filterGeneratedChecksByType(string $class): array
    {
        $filtered = array_filter(
            $this->generatedChecks,
            function ($generatedCheckResponse) use ($class): bool {
                return $generatedCheckResponse instanceof $class;
            }
        );

        return array_values($filtered);
    }
}
