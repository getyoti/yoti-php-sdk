<?php

namespace Yoti\IDV\Session\Retrieve\Configuration\Capture\Source;

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
