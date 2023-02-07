<?php

declare(strict_types=1);

namespace Yoti\IDV\Session\Create\Task;

use Yoti\IDV\Constants;

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
