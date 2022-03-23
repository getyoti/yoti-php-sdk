<?php

namespace Yoti\DocScan\Session\Create;

use JsonSerializable;
use Yoti\Util\Json;

class IbvOptions implements JsonSerializable
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


    /**
     * @return \stdClass
     */
    public function jsonSerialize(): \stdClass
    {
        return (object)Json::withoutNullValues([
            'support' => $this->getSupport(),
            'guidance_url' => $this->getGuidanceUrl(),
        ]);
    }
}
