<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source;

abstract class AllowedSourceResponse
{
    /**
     * @var string
     */
    private $type;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
