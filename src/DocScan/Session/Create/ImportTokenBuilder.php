<?php

namespace Yoti\DocScan\Session\Create;

use Yoti\DocScan\Exception\DocScanException;

class ImportTokenBuilder
{
    private const DEFAULT_TTL = 3600 * 24 * 365;

    private int $ttl;

    public function withTtl(int $ttl = null): ImportTokenBuilder
    {
        $this->ttl = $ttl ?? self::DEFAULT_TTL;

        return $this;
    }

    /**
     * @throws DocScanException
     */
    public function build(): ImportToken
    {
        return new ImportToken($this->ttl);
    }
}
