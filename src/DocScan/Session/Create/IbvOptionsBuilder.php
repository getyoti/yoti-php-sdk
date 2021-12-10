<?php

namespace Yoti\DocScan\Session\Create;

use Yoti\DocScan\Constants;

class IbvOptionsBuilder
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
     * @return $this
     */
    public function withIbvNotAllowed(): IbvOptionsBuilder
    {
        return $this->withSupport(Constants::NOT_ALLOWED);
    }

    /**
     * @return $this
     */
    public function withIbvMandatory(): IbvOptionsBuilder
    {
        return $this->withSupport(Constants::MANDATORY);
    }

    /**
     * @param string $support
     * @return $this
     */
    public function withSupport(string $support): IbvOptionsBuilder
    {
        $this->support = $support;
        return $this;
    }

    /**
     * @param string $guidanceUrl
     * @return $this
     */
    public function withGuidanceUrl(string $guidanceUrl): IbvOptionsBuilder
    {
        $this->guidanceUrl = $guidanceUrl;
        return $this;
    }

    /**
     * @return IbvOptions
     */
    public function build(): IbvOptions
    {
        return new IbvOptions($this->support, $this->guidanceUrl);
    }
}
