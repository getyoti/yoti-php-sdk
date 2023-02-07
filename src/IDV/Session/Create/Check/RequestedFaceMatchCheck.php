<?php

declare(strict_types=1);

namespace Yoti\IDV\Session\Create\Check;

use Yoti\IDV\Constants;

class RequestedFaceMatchCheck extends RequestedCheck
{
    /**
     * @var RequestedFaceMatchCheckConfig
     */
    private $config;

    public function __construct(RequestedFaceMatchCheckConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    protected function getType(): string
    {
        return Constants::ID_DOCUMENT_FACE_MATCH;
    }

    /**
     * @inheritDoc
     */
    protected function getConfig(): ?RequestedCheckConfigInterface
    {
        return $this->config;
    }
}
