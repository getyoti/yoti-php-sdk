<?php

namespace Yoti\DocScan\Session\Retrieve;

use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\AllowedSourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\EndUserAllowedSourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\IbvAllowedSourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\RelyingBusinessAllowedSourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\UnknownAllowedSourceResponse;

class ResourceResponse
{
    /**
     * @var string|null
     */
    private $id;

    /**
     * @var TaskResponse[]
     */
    private $tasks = [];

    /**
     * @var AllowedSourceResponse
     */
    private $source;

    /**
     * ResourceResponse constructor.
     * @param array<string, mixed> $resource
     */
    public function __construct(array $resource)
    {
        $this->id = $resource['id'] ?? null;

        if (isset($resource['tasks'])) {
            foreach ($resource['tasks'] as $task) {
                $this->tasks[] = $this->createTaskFromArray($task);
            }
        }

        if (isset($resource['source']['type'])) {
            $this->source = $this->createSourceFromType($resource['source']['type']);
        }
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return TaskResponse[]
     */
    public function getTasks(): array
    {
        return $this->tasks;
    }

    /**
     * @return AllowedSourceResponse
     */
    public function getSource(): AllowedSourceResponse
    {
        return $this->source;
    }

    /**
     * @return TaskResponse[]
     */
    public function getTextExtractionTasks(): array
    {
        return $this->filterTasksByType(TextExtractionTaskResponse::class);
    }

    /**
     * @param string $class
     * @return mixed[]
     */
    protected function filterTasksByType(string $class): array
    {
        $filtered = array_filter(
            $this->tasks,
            function ($taskResponse) use ($class): bool {
                return $taskResponse instanceof $class;
            }
        );

        return array_values($filtered);
    }

    /**
     * @param array<string, mixed> $task
     * @return TaskResponse
     */
    private function createTaskFromArray(array $task): TaskResponse
    {
        switch ($task['type'] ?? null) {
            case Constants::ID_DOCUMENT_TEXT_DATA_EXTRACTION:
                return new TextExtractionTaskResponse($task);
            case Constants::SUPPLEMENTARY_DOCUMENT_TEXT_DATA_EXTRACTION:
                return new SupplementaryDocTextExtractionTaskResponse($task);
            default:
                return new TaskResponse($task);
        }
    }

    /**
     * @param string $type
     * @return AllowedSourceResponse
     */
    private function createSourceFromType(string $type): AllowedSourceResponse
    {
        switch ($type ?? null) {
            case Constants::END_USER:
                return new EndUserAllowedSourceResponse();
            case Constants::IBV:
                return new IbvAllowedSourceResponse();
            case Constants::RELYING_BUSINESS:
                return new RelyingBusinessAllowedSourceResponse();
            default:
                return new UnknownAllowedSourceResponse();
        }
    }
}
