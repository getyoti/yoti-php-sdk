<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Task;

use Yoti\DocScan\Constants;

class RequestedSupplementaryDocTextExtractionTask extends RequestedTask
{
    /**
     * @var RequestedSupplementaryDocTextExtractionTaskConfig
     */
    private $config;

    /**
     * @param RequestedSupplementaryDocTextExtractionTaskConfig $config
     */
    public function __construct(RequestedSupplementaryDocTextExtractionTaskConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    protected function getType(): string
    {
        return Constants::SUPPLEMENTARY_DOCUMENT_TEXT_DATA_EXTRACTION;
    }

    /**
     * @inheritDoc
     */
    protected function getConfig(): ?RequestedTaskConfigInterface
    {
        return $this->config;
    }
}
