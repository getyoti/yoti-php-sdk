<?php

namespace Yoti\Entity;


class SignedTimeStamp
{
    /**
     * Version indicates how the digests within the SignedTimeStamp object are calculated.
     *
     * @var int
     */
    private $version;

    /**
     * Timestamp is the time the anchor was issued. It is in UTC,
     * as µseconds elapsed since the epoch (µs from 1970-01-01T00:00:00Z).
     *
     * @var int
     */
    private $value;

    public function __construct($value, $version)
    {
        $this->value = $value;
        $this->version = $version;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getVersion()
    {
        return $this->version;
    }
}