<?php

namespace Yoti\Identity\Extension;

use stdClass;
use Yoti\Profile\ExtraData\AttributeDefinition;
use Yoti\Util\Validation;

class ThirdPartyAttributeContent implements \JsonSerializable
{
    /**
     * @var AttributeDefinition[]
     */
    private array $definitions;

    private \DateTime $expiryDate;

    /**
     * @param AttributeDefinition[] $definitions
     */
    public function __construct(\DateTime $expiryDate, array $definitions)
    {
        $this->expiryDate = $expiryDate;

        Validation::isArrayOfType($definitions, [AttributeDefinition::class], 'definitions');
        $this->definitions = $definitions;
    }

    public function jsonSerialize(): stdClass
    {
        return (object)[
            'expiry_date' => $this->expiryDate
                ->setTimezone(new \DateTimeZone('UTC'))
                ->format(\DateTime::RFC3339_EXTENDED),
            'definitions' => $this->definitions,
        ];
    }
}
