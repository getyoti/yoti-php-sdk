<?php

namespace Yoti\ShareUrl\Extension;

use Yoti\Entity\AttributeDefinition;
use Yoti\Util\Validation;

/**
 * Builds third party attribute extension.
 */
class ThirdPartyAttributeExtensionBuilder
{
    /**
     * Third Party Attribute Extension Type.
     */
    const THIRD_PARTY_ATTRIBUTE = 'THIRD_PARTY_ATTRIBUTE';

    /**
     * @var \Yoti\Entity\AttributeDefinition[]
     */
    private $definitions = [];

    /**
     * @var \DateTime
     */
    private $expiryDate;

    /**
     * @param \DateTime $expiryDate
     *
     * @return \Yoti\ShareUrl\Extension\ThirdPartyAttributeExtensionBuilder
     */
    public function withExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;
        return $this;
    }

    /**
     * @param string $definition
     *
     * @return \Yoti\ShareUrl\Extension\ThirdPartyAttributeExtensionBuilder
     */
    public function withDefinition($definition)
    {
        Validation::isString($definition, 'definition');
        $this->definitions[] = new AttributeDefinition($definition);
        return $this;
    }

    /**
     * @param string $definition
     *
     * @return \Yoti\ShareUrl\Extension\ThirdPartyAttributeExtensionBuilder
     */
    public function withDefinitions($definitions)
    {
        Validation::isArrayOfStrings($definitions, 'definitions');
        $this->definitions = array_map(
            function ($definition) {
                return new AttributeDefinition($definition);
            },
            $definitions
        );
        return $this;
    }

    /**
     * @return \Yoti\ShareUrl\Extension\Extension
     */
    public function build()
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
