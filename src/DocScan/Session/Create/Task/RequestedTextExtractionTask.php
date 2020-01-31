<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Task;

use Yoti\DocScan\Constants;

class RequestedTextExtractionTask extends RequestedTask
{

    /**
     * @var RequestedTextExtractionTaskConfig
     */
    private $config;

    public function __construct(RequestedTextExtractionTaskConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    protected function getType(): string
    {
        return Constants::ID_DOCUMENT_TEXT_DATA_EXTRACTION;
    }

    /**
     * @inheritDoc
     */
    protected function getConfig(): ?RequestedTaskConfigInterface
    {
        return $this->config;
    }
}
