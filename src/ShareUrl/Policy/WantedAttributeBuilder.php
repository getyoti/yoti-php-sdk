<?php

declare(strict_types=1);

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
     * @var bool|null
     */
    private $acceptSelfAsserted;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function withName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $derivation
     *
     * @return $this
     */
    public function withDerivation(string $derivation): self
    {
        $this->derivation = $derivation;
        return $this;
    }

    /**
     * @param \Yoti\ShareUrl\Policy\Constraints $constraints
     *
     * @return $this
     */
    public function withConstraints(Constraints $constraints): self
    {
        $this->constraints = $constraints;
        return $this;
    }

    /**
     * @param bool $acceptSelfAsserted
     *
     * @return $this
     */
    public function withAcceptSelfAsserted(?bool $acceptSelfAsserted = true): self
    {
        $this->acceptSelfAsserted = $acceptSelfAsserted;
        return $this;
    }

    /**
     * @return \Yoti\ShareUrl\Policy\WantedAttribute
     */
    public function build(): WantedAttribute
    {
        return new WantedAttribute(
            $this->name,
            $this->derivation,
            $this->acceptSelfAsserted,
            $this->constraints
        );
    }
}
