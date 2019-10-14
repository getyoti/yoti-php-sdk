<?php

namespace Yoti\Entity;

class CredentialIssuanceDetails
{
    /**
     * @param string $token
     * @param \DateTime $expiryDate
     * @param array $issuingAttributes
     */
    public function __construct($token, \DateTime $expiryDate, array $issuingAttributes)
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
     * @return \DateTime
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
