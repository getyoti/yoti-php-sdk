<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check;

use Yoti\DocScan\Constants;

class RequestedLivenessCheck extends RequestedCheck
{

    /**
     * @var RequestedLivenessConfig
     */
    private $config;

    /**
     * RequestedLivenessCheck constructor.
     * @param RequestedLivenessConfig $config
     */
    public function __construct(RequestedLivenessConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    protected function getType(): string
    {
        return Constants::LIVENESS;
    }

    /**
     * @inheritDoc
     */
    protected function getConfig(): ?RequestedCheckConfigInterface
    {
        return $this->config;
    }
}
