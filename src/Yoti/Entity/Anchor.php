<?php

namespace Yoti\Entity;


class Anchor
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @var string
     */
    protected $subType;

    /**
     * @var \Protobuf\Stream
     */
    protected $signature;

    protected $artifactSignature;

    /**
     * @var \Protobuf\Stream
     */
    protected $signedTimeStamp;

    /**
     * @var int
     */
    protected $timeStamp;

    /**
     * @var array
     */
    protected $originServerCerts;

    public function __construct(
        $value,
        $subType,
        $signature,
        $artifactSignature,
        $signedTimeStamp,
        array $originServerCerts
    ) {
        $this->value = $value;
        $this->subType = $subType;
        $this->signature = $signature;
        $this->artifactSignature = $artifactSignature;
        $this->signedTimeStamp = $signedTimeStamp;
        $this->originServerCerts = $originServerCerts;

        $config = new \Protobuf\Configuration();
        $streamReader = $config->getStreamReader();
        $this->timeStamp = $streamReader->readFixed32($this->signedTimeStamp);
    }

    /**
     * @return string
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getSubtype() {
        return $this->subType;
    }

    /**
     * @return \Protobuf\Stream
     */
    public function getSignature() {
        return $this->signature;
    }

    /**
     * @return \Protobuf\Stream
     */
    public function getArtifactSignature() {
        return $this->artifactSignature;
    }

    /**
     * @return \Protobuf\Stream
     */
    public function getSignedTimeStamp() {
        return $this->signedTimeStamp;
    }

    /**
     * @return int
     */
    public function getTimeStamp() {
        return $this->timeStamp;
    }

    /**
     * @return array of X509 certs
     */
    public function getOriginServerCerts() {
        return  $this->originServerCerts;
    }
}