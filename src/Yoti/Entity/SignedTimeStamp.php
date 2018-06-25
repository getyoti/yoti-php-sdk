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
     * PHP DateTime object.
     *
     * @var \DateTime
     */
    private $timestamp;

    /**
     * SignedTimeStamp constructor.
     *
     * @param $version
     * @param \DateTime $timestamp
     */
    public function __construct($version, \DateTime $timestamp)
    {
        $this->version = $version;
        $this->timestamp = $timestamp;
    }

    /**
     * Return PHP DateTime object.
     *
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }
}