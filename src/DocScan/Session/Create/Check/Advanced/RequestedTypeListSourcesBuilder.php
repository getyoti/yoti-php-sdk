<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create\Check\Advanced;

class RequestedTypeListSourcesBuilder
{
    /**
     * @var string[]
     */
    private $types;

    private function __construct()
    {
    }

    /**
     * @param string[] $types
     * @return $this
     */
    public function withTypes(array $types): RequestedTypeListSourcesBuilder
    {
        $this->types = $types;
        return $this;
    }

    /**
     * @return RequestedTypeListSources
     */
    public function build(): RequestedTypeListSources
    {
        return new RequestedTypeListSources($this->types);
    }
}
