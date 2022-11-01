<?php

namespace Yoti\DocScan\Session\Create\Check;

use Yoti\DocScan\Constants;

class RequestedFaceComparisonCheck extends RequestedCheck
{
    /**
     * @var RequestedFaceComparisonCheckConfig
     */
    private $config;

    public function __construct(RequestedFaceComparisonCheckConfig $config)
    {
        $this->config = $config;
    }

    protected function getType(): string
    {
        return Constants::FACE_COMPARISON;
    }

    protected function getConfig(): ?RequestedCheckConfigInterface
    {
        return $this->config;
    }
}
