<?php

namespace Yoti\DocScan\Session\Create;

class IbvOptions
{
    /**
     * @var string
     */
    private $support;

    /**
     * @var string|null
     */
    private $guidanceUrl;

    /**
     * @param string $support
     * @param string|null $guidanceUrl
     */
    public function __construct(string $support, ?string $guidanceUrl = null)
    {
        $this->support = $support;
        $this->guidanceUrl = $guidanceUrl;
    }

    /**
     * @return string
     */
    public function getSupport(): string
    {
        return $this->support;
    }

    /**
     * @return string
     */
    public function getGuidanceUrl(): ?string
    {
        return $this->guidanceUrl;
    }
}
