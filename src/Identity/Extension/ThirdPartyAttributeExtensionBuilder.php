<?php

namespace Yoti\Identity\Extension;

use Yoti\Profile\ExtraData\AttributeDefinition;
use Yoti\Util\Validation;

/**
 * Builds a third party attribute Extension.
 */
class ThirdPartyAttributeExtensionBuilder implements ExtensionBuilderInterface
{
    /**
     * Third Party Attribute Extension Type.
     */
    private const THIRD_PARTY_ATTRIBUTE = 'THIRD_PARTY_ATTRIBUTE';

    /**
     * @var AttributeDefinition[]
     */
    private array $definitions = [];


    private \DateTime $expiryDate;

    public function withExpiryDate(\DateTime $expiryDate): self
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    public function withDefinition(string $definition): self
    {
        $this->definitions[] = new AttributeDefinition($definition);

        return $this;
    }

    /**
     * @param string[] $definitions
     */
    public function withDefinitions(array $definitions): self
    {
        Validation::isArrayOfStrings($definitions, 'definitions');
        $this->definitions = array_map(
            function ($definition): AttributeDefinition {
                return new AttributeDefinition($definition);
            },
            $definitions
        );

        return $this;
    }

    public function build(): Extension
    {
        return new Extension(
            self::THIRD_PARTY_ATTRIBUTE,
            new ThirdPartyAttributeContent(
                $this->expiryDate,
                $this->definitions
            )
        );
    }
}
