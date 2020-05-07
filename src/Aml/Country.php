<?php

declare(strict_types=1);

namespace Yoti\Aml;

class Country implements \JsonSerializable
{
    /**
     * Country code.
     *
     * @var string
     */
    private $code;

    /**
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function jsonSerialize(): string
    {
        return $this->getCode();
    }
}
