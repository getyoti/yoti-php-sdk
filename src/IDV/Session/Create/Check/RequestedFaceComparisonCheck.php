<?php

namespace Yoti\IDV\Session\Create\Check;

use Yoti\IDV\Constants;

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
