<?php

declare(strict_types=1);

namespace Yoti\ShareUrl\Extension;

use Yoti\Profile\ExtraData\AttributeDefinition;
use Yoti\Util\Validation;

/**
 * Builds third party attribute extension.
 */
class ThirdPartyAttributeExtensionBuilder
{
    /**
     * Third Party Attribute Extension Type.
     */
    private const THIRD_PARTY_ATTRIBUTE = 'THIRD_PARTY_ATTRIBUTE';

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
     *
     * @return $this
     */
    public function withExpiryDate(\DateTime $expiryDate): self
    {
        $this->expiryDate = $expiryDate;
        return $this;
    }

    /**
     * @param string $definition
     *
     * @return $this
     */
    public function withDefinition(string $definition): self
    {
        $this->definitions[] = new AttributeDefinition($definition);
        return $this;
    }

    /**
     * @param string[] $definitions
     *
     * @return $this
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

    /**
     * @return \Yoti\ShareUrl\Extension\Extension
     */
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
