<?php

declare(strict_types=1);

namespace Yoti\ShareUrl\Extension;

use Yoti\Profile\ExtraData\AttributeDefinition;
use Yoti\Util\Json;
use Yoti\Util\Validation;

/**
 * Defines a third party attribute.
 */
class ThirdPartyAttributeContent implements \JsonSerializable
{
    /**
     * @var \Yoti\Profile\ExtraData\AttributeDefinition[]
     */
    private $definitions = [];

    /**
     * @var \DateTime
     */
    private $expiryDate;

    /**
     * @param \DateTime $expiryDate
     * @param \Yoti\Profile\ExtraData\AttributeDefinition[] $definitions
     */
    public function __construct(\DateTime $expiryDate, array $definitions)
    {
        $this->expiryDate = $expiryDate;

        Validation::isArrayOfType($definitions, [AttributeDefinition::class], 'definitions');
        $this->definitions = $definitions;
    }

    /**
     * @inheritDoc
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'expiry_date' => $this->expiryDate
                ->setTimezone(new \DateTimeZone('UTC'))
                ->format(\DateTime::RFC3339_EXTENDED),
            'definitions' => $this->definitions,
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return Json::encode($this);
    }
}
