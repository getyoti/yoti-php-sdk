<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture\Task;

class RequestedIdDocTaskResponse extends RequestedTaskResponse
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
