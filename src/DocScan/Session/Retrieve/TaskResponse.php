<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

use Yoti\DocScan\Constants;

class TaskResponse
{

    /**
     * @var string|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $state;

    /**
     * @var string|null
     */
    private $created;

    /**
     * @var string|null
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
        $this->id = $task['id'] ?? null;
        $this->state = $task['state'] ?? null;
        $this->created = $task['created'] ?? null;
        $this->lastUpdated = $task['last_updated'] ?? null;

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
     * @return string|null
     */
    public function getCreated(): ?string
    {
        return $this->created;
    }

    /**
     * @return string|null
     */
    public function getLastUpdated(): ?string
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
     * @return GeneratedTextDataCheckResponse[]
     */
    public function getGeneratedTextDataChecks(): array
    {
        return $this->filterGeneratedChecksByType(GeneratedTextDataCheckResponse::class);
    }

    /**
     * @param string $class
     * @return mixed[]
     */
    private function filterGeneratedChecksByType(string $class): array
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
