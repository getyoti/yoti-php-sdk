<?php

namespace Yoti\ShareUrl\Policy;

/**
 * Builder for WantedAttribute.
 */
class WantedAttributeBuilder
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $derivation;

    /**
     * @var \Yoti\ShareUrl\Policy\Constraints
     */
    private $constraints;

    /**
     * @var boolean
     */
    private $acceptSelfAsserted;

    /**
     * @param string $name
     *
     * @return \Yoti\ShareUrl\Policy\WantedAttributeBuilder
     */
    public function withName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $derivation
     *
     * @return \Yoti\ShareUrl\Policy\WantedAttributeBuilder
     */
    public function withDerivation($derivation)
    {
        $this->derivation = $derivation;
        return $this;
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     *
     * @return \Yoti\ShareUrl\Policy\WantedAttributeBuilder
     */
    public function withConstraints(Constraints $constraints)
    {
        $this->constraints = $constraints;
        return $this;
    }

    /**
     * @param boolean $acceptSelfAsserted
     *
     * @return \Yoti\ShareUrl\Policy\WantedAttributeBuilder
     */
    public function withAcceptSelfAsserted($acceptSelfAsserted = true)
    {
        $this->acceptSelfAsserted = $acceptSelfAsserted;
        return $this;
    }

    /**
     * @return \Yoti\ShareUrl\Policy\WantedAttribute
     *
     * @return \Yoti\ShareUrl\Policy\WantedAttributeBuilder
     */
    public function build()
    {
        return new WantedAttribute(
            $this->name,
            $this->derivation,
            $this->acceptSelfAsserted,
            $this->constraints
        );
    }
}
