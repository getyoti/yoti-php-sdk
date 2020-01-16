<?php

declare(strict_types=1);

namespace Yoti\Profile\Attribute;

class SignedTimeStamp
{
    /**
     * Version indicates how the digests within the SignedTimeStamp object are calculated.
     *
     * @var int
     */
    private $version;

    /**
     * PHP DateTime object.
     *
     * @var \DateTime
     */
    private $timestamp;

    /**
     * SignedTimeStamp constructor.
     *
     * @param int $version
     * @param \DateTime $timestamp
     */
    public function __construct(int $version, \DateTime $timestamp)
    {
        $this->version = $version;
        $this->timestamp = $timestamp;
    }

    /**
     * Return PHP DateTime object.
     *
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }
}
