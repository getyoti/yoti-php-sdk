<?php

namespace Yoti\DocScan\Session\Create;

use JsonSerializable;
use Yoti\DocScan\Exception\DocScanException;
use Yoti\Util\Json;

class ImportToken implements JsonSerializable
{
    private const MIN_TTL = 3600 * 24 * 30;
    private const MAX_TTL = 3600 * 24 * 365;

    private int $ttl;

    /**
     * @throws DocScanException
     */
    public function __construct(int $ttl)
    {
        $this->validate($ttl);
        $this->ttl = $ttl;
    }

    public function jsonSerialize(): \stdClass
    {
        return (object)Json::withoutNullValues([
            'ttl' => $this->getTtl(),
        ]);
    }

    /**
     * @return int
     */
    public function getTtl(): int
    {
        return $this->ttl;
    }

    /**
     * @throws DocScanException
     */
    private function validate(int $ttl): void
    {
        if (self::MAX_TTL < $ttl || self::MIN_TTL > $ttl) {
            throw new DocScanException(
                'Your TTL is invalid. Min value - ' . self::MIN_TTL . '.Max value - ' . self::MAX_TTL . '.'
            );
        }
    }
}
