<?php

namespace Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source;

abstract class AllowedSourceResponse
{
    /**
     * @var string|null
     */
    protected $type;

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }
}
