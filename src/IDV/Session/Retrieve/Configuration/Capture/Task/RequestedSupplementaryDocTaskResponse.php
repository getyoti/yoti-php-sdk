<?php

namespace Yoti\IDV\Session\Retrieve\Configuration\Capture\Task;

class RequestedSupplementaryDocTaskResponse extends RequestedTaskResponse
{
    /**
     * @param array<string, string> $requestedTask
     */
    public function __construct(array $requestedTask)
    {
        $this->type = $requestedTask['type'] ?? null;
        $this->state = $requestedTask['state'] ?? null;
    }
}
