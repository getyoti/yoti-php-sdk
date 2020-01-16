<?php

declare(strict_types=1);

namespace Yoti\Profile\ExtraData;

use Yoti\Util\Validation;

class AttributeIssuanceDetails
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var \DateTime|null
     */
    private $expiryDate;

    /**
     * @var \Yoti\Profile\ExtraData\AttributeDefinition[]
     */
    private $issuingAttributes;

    /**
     * @param string $token
     * @param \DateTime $expiryDate
     * @param \Yoti\Profile\ExtraData\AttributeDefinition[] $issuingAttributes
     */
    public function __construct(string $token, \DateTime $expiryDate = null, array $issuingAttributes = [])
    {
        $this->token = $token;

        $this->expiryDate = $expiryDate;

        Validation::isArrayOfType($issuingAttributes, [AttributeDefinition::class], 'issuingAttributes');
        $this->issuingAttributes = $issuingAttributes;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return \DateTime|null
     */
    public function getExpiryDate(): ?\DateTime
    {
        return $this->expiryDate;
    }

    /**
     * @return \Yoti\Profile\ExtraData\AttributeDefinition[]
     */
    public function getIssuingAttributes(): array
    {
        return $this->issuingAttributes;
    }
}
