<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Filters;

use JsonSerializable;
use stdClass;
use Yoti\Util\Json;

class RequiredShareCode implements JsonSerializable
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
     * @param string|null $issuer
     * @param string|null $scheme
     */
    public function __construct(?string $issuer = null, ?string $scheme = null)
    {
        $this->issuer = $issuer;
        $this->scheme = $scheme;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize(): stdClass
    {
        return (object) Json::withoutNullValues([
            'issuer' => $this->issuer,
            'scheme' => $this->scheme,
        ]);
    }

    /**
     * @return string|null
     */
    public function getIssuer(): ?string
    {
        return $this->issuer;
    }

    /**
     * @return string|null
     */
    public function getScheme(): ?string
    {
        return $this->scheme;
    }
}
