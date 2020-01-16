<?php

declare(strict_types=1);

namespace Yoti\ShareUrl\Policy;

/**
 * Builder for WantedAnchor.
 */
class WantedAnchorBuilder
{
    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $subType = '';

    /**
     * @param string $value
     *
     * @return $this
     */
    public function withValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param string $subType
     *
     * @return $this
     */
    public function withSubType(string $subType): self
    {
        $this->subType = $subType;
        return $this;
    }

    /**
     * @return \Yoti\ShareUrl\Policy\WantedAnchor
     */
    public function build(): WantedAnchor
    {
        return new WantedAnchor($this->value, $this->subType);
    }
}
