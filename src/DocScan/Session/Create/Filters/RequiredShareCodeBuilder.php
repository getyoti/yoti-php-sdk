<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters;

class RequiredShareCodeBuilder
{
    /**
     * @var string|null
     */
    private $issuer;

    /**
     * @var string|null
     */
    private $scheme;

    /**
     * @param string $issuer
     *
     * @return $this
     */
    public function withIssuer(string $issuer): self
    {
        $this->issuer = $issuer;
        return $this;
    }

    /**
     * @param string $scheme
     *
     * @return $this
     */
    public function withScheme(string $scheme): self
    {
        $this->scheme = $scheme;
        return $this;
    }

    /**
     * @return RequiredShareCode
     */
    public function build(): RequiredShareCode
    {
        return new RequiredShareCode($this->issuer, $this->scheme);
    }
}
