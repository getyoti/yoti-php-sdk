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
     * @var \Google\Protobuf\ bytes signature
     */
    protected $signature;

    /**
     * @var \Compubapi_v1\SignedTimestamp
     */
    protected $signedTimeStamp;

    /**
     * @var array
     */
    protected $originServerCerts;

    public function __construct(
        $value,
        $subType,
        $signature,
        $signedTimeStamp,
        array $originServerCerts
    ) {
        $this->value = $value;
        $this->subType = $subType;
        $this->signature = $signature;
        $this->signedTimeStamp = $signedTimeStamp;
        $this->originServerCerts = $originServerCerts;
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
     * @return \Google\Protobuf\ bytes signature
     */
    public function getSignature() {
        return $this->signature;
    }

    /**
     * @return \Compubapi_v1\SignedTimestamp
     */
    public function getSignedTimeStamp() {
        return $this->signedTimeStamp;
    }

    /**
     * @return array of X509 certs
     */
    public function getOriginServerCerts() {
        return  $this->originServerCerts;
    }
}