<?php

declare(strict_types=1);

namespace Yoti\ShareUrl\Policy;

/**
 * Builder for SourceConstraint.
 */
class SourceConstraintBuilder
{
    /**
     * @var \Yoti\ShareUrl\Policy\WantedAnchor[]
     */
    private $anchors = [];

    /**
     * @var bool
     */
    private $softPreference = false;

    /**
     * @param \Yoti\ShareUrl\Policy\WantedAnchor $anchor
     *
     * @return $this
     */
    public function withAnchor(WantedAnchor $anchor): self
    {
        $this->anchors[] = $anchor;
        return $this;
    }

    /**
     * @param bool $softPreference
     *
     * @return $this
     */
    public function withSoftPreference(bool $softPreference = true): self
    {
        $this->softPreference = $softPreference;
        return $this;
    }

    /**
     * @param string $value
     * @param string $subType
     *
     * @return $this
     */
    public function withAnchorByValue(string $value, string $subType = ''): self
    {
        $this->anchors[] = (new WantedAnchorBuilder())
            ->withValue($value)
            ->withSubType($subType)
            ->build();
        return $this;
    }

    /**
     * @param string $subType
     *
     * @return $this
     */
    public function withPassport(string $subType = ''): self
    {
        return $this->withAnchorByValue(WantedAnchor::VALUE_PASSPORT, $subType);
    }

    /**
     * @param string $subType
     *
     * @return $this
     */
    public function withDrivingLicence(string $subType = ''): self
    {
        return $this->withAnchorByValue(WantedAnchor::VALUE_DRIVING_LICENSE, $subType);
    }

    /**
     * @param string $subType
     *
     * @return $this
     */
    public function withNationalId(string $subType = ''): self
    {
        return $this->withAnchorByValue(WantedAnchor::VALUE_NATIONAL_ID, $subType);
    }

    /**
     * @param string $subType
     *
     * @return $this
     */
    public function withPasscard(string $subType = ''): self
    {
        return $this->withAnchorByValue(WantedAnchor::VALUE_PASSCARD, $subType);
    }

    /**
     * @return \Yoti\ShareUrl\Policy\SourceConstraint
     */
    public function build(): SourceConstraint
    {
        return new SourceConstraint($this->anchors, $this->softPreference);
    }
}
