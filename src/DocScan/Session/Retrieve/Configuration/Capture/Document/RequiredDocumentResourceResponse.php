<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture\Document;

use Yoti\DocScan\Session\Retrieve\Configuration\Capture\RequiredResourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Task\RequestedTaskResponse;

abstract class RequiredDocumentResourceResponse extends RequiredResourceResponse
{
    /**
     * @var array<int, RequestedTaskResponse>
     */
    private $requestedTasks;

    /**
     * Returns any tasks that need to be completed as part of the document
     * requirement.
     *
     * @return RequestedTaskResponse[]
     */
    public function getRequestedTasks(): array
    {
        return $this->requestedTasks;
    }
}
