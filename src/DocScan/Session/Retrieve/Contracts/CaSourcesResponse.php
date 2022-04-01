<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve\Contracts;

abstract class CaSourcesResponse
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
