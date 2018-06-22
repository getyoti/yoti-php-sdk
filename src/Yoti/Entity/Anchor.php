<?php

namespace Yoti\Entity;

use Yoti\Entity\SignedTimeStamp;

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
     * @var \Yoti\Entity\SignedTimeStamp
     */
    protected $signedTimeStamp;

    /**
     * @var array
     */
    protected $originServerCerts;

    public function __construct(
        $value,
        $subType,
        SignedTimeStamp $signedTimeStamp,
        array $originServerCerts
    ) {
        $this->value = $value;
        $this->subType = $subType;
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
     * @return \Yoti\Entity\SignedTimeStamp
     */
    public function getSignedTimeStamp()
    {
        return $this->signedTimeStamp;
    }

    /**
     * @return array of X509 certs
     */
    public function getOriginServerCerts() {
        return  $this->originServerCerts;
    }
}