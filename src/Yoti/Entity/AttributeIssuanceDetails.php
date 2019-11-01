<?php

namespace Yoti\Entity;

use Yoti\Util\Validation;

class AttributeIssuanceDetails
{
    /**
     * @param string $token
     * @param \DateTime $expiryDate
     * @param \Yoti\Entity\AttributeDefinition[] $issuingAttributes
     */
    public function __construct($token, \DateTime $expiryDate = null, array $issuingAttributes = [])
    {
        Validation::isString($token, 'token');
        $this->token = $token;

        $this->expiryDate = $expiryDate;

        Validation::isArrayOfType($issuingAttributes, [AttributeDefinition::class], 'issuingAttributes');
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
     * @return \Yoti\Entity\AttributeDefinition[]
     */
    public function getIssuingAttributes()
    {
        return $this->issuingAttributes;
    }
}
