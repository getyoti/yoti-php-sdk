<?php

namespace Yoti\DocScan\Session\Retrieve;

use Yoti\DocScan\Constants;

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
}
