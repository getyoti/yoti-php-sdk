<?php

namespace Yoti\Entity;

class AttributeIssuanceDetails
{
    /**
     * @param string $token
     * @param \DateTime $expiryDate
     * @param array $issuingAttributes
     */
    public function __construct($token, \DateTime $expiryDate = null, array $issuingAttributes = [])
    {
        $this->token = $token;
        $this->expiryDate = $expiryDate;
        $this->issuingAttributes = $issuingAttributes;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return \DateTime|null
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * @return array
     */
    public function getIssuingAttributes()
    {
        return $this->issuingAttributes;
    }
}
