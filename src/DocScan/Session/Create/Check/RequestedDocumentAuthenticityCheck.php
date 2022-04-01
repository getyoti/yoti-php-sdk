<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

use Yoti\DocScan\Constants;

class RequestedDocumentAuthenticityCheck extends RequestedCheck
{
    /**
     * @var RequestedDocumentAuthenticityCheckConfig|null
     */
    private $config;

    public function __construct(?RequestedDocumentAuthenticityCheckConfig $config = null)
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return Constants::ID_DOCUMENT_AUTHENTICITY;
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): ?RequestedCheckConfigInterface
    {
        return $this->config;
    }
}
