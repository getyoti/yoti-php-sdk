<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture\Task;

class RequestedSupplementaryDocTaskResponse extends RequestedTaskResponse
{
    /**
     * @param array<string, string> $requestedTask
     */
    public function __construct(array $requestedTask)
    {
        $this->type = $requestedTask['task'] ?? null;
        $this->state = $requestedTask['state'] ?? null;
    }
}
