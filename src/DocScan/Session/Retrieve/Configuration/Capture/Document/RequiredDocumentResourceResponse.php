<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture\Document;

use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\RequiredResourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Task\RequestedIdDocTaskResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Task\RequestedSupplementaryDocTaskResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Task\RequestedTaskResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Task\UnknownRequestedTaskResponse;

abstract class RequiredDocumentResourceResponse extends RequiredResourceResponse
{
    /**
     * @var array<int, RequestedTaskResponse>
     */
    protected $requestedTasks;

    /**
     * @param array<string, string> $requestedTask
     * @return RequestedTaskResponse
     */
    protected function createTaskFromArray(array $requestedTask): RequestedTaskResponse
    {
        switch ($requestedTask['type'] ?? null) {
            case Constants::ID_DOCUMENT_TEXT_DATA_EXTRACTION:
                return new RequestedIdDocTaskResponse($requestedTask);
            case Constants::SUPPLEMENTARY_DOCUMENT_TEXT_DATA_EXTRACTION:
                return new RequestedSupplementaryDocTaskResponse($requestedTask);
            default:
                return new UnknownRequestedTaskResponse();
        }
    }

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
