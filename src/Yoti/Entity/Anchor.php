<?php

namespace Yoti\Entity;

class Anchor
{
    const TYPE_SOURCE_NAME = 'Source';
    const TYPE_VERIFIER_NAME = 'Verifier';
    const TYPE_SOURCE_OID = '1.3.6.1.4.1.47127.1.1.1';
    const TYPE_VERIFIER_OID = '1.3.6.1.4.1.47127.1.1.2';

    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $subType;

    /**
     * @var \Yoti\Entity\SignedTimeStamp
     */
    private $signedTimeStamp;

    /**
     * @var array
     */
    private $originServerCerts;

    public function __construct(
        $value,
        $type,
        $subType,
        SignedTimeStamp $signedTimeStamp,
        array $originServerCerts
    ) {
        $this->value = $value;
        $this->type = $type;
        $this->subType = $subType;
        $this->signedTimeStamp = $signedTimeStamp;
        $this->originServerCerts = $originServerCerts;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getSubtype()
    {
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