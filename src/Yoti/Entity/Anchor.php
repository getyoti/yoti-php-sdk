<?php

namespace Yoti\Entity;


class Anchor
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @var \Protobuf\Stream
     */
    protected $artifactLink;

    /**
     * @var string
     */
    protected $subType;

    /**
     * @var \Protobuf\Stream
     */
    protected $signature;

    /**
     * @var \Protobuf\Stream
     */
    protected $signedTimeStamp;

    /**
     * @var array
     */
    protected $originServerCerts;

    /**
     * @var
     */
    protected $associatedSource;

    public function __construct(
        $value,
        $artifactLink,
        $subType,
        $signature,
        $signedTimeStamp,
        $originServerCerts,
        $associatedSource
    ) {
        $this->value = $value;
        $this->artifactLink = $artifactLink;
        $this->subType = $subType;
        $this->signature = $signature;
        $this->signedTimeStamp = $signedTimeStamp;
        $this->originServerCerts = $originServerCerts;
        $this->associatedSource = $associatedSource;
    }

    public function getValue() {
        return $this->value;
    }

    public function getArtifactLink() {
        return $this->artifactLink;
    }

    public function getSubtype() {
        return $this->subType;
    }

    public function getSignature() {
        return $this->signature;
    }

    public function getSignedTimeStamp() {
        return $this->signedTimeStamp;
    }

    public function getOriginServerCerts() {
        return  $this->originServerCerts;
    }

    public function getAssociatedSource() {
        return $this->associatedSource;
    }
}