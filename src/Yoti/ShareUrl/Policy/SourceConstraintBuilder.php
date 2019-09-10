<?php

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
     * @var boolean
     */
    private $softPreference = false;

    /**
     * @param \Yoti\ShareUrl\Policy\WantedAnchor anchor
     */
    public function withAnchor(WantedAnchor $anchor)
    {
        $this->anchors = $anchor;
        return $this;
    }

    /**
     * @param boolean $softPreference
     */
    public function withSoftPreference($softPreference = true)
    {
        $this->softPreference = $softPreference;
        return $this;
    }

    /**
     * @param {string} value
     * @param {string} subType
     */
    public function withAnchorByValue($value, $subType = '')
    {
        $this->anchors[] = (new WantedAnchorBuilder())
            ->withValue($value)
            ->withSubType($subType)
            ->build();
        return $this;
    }

    /**
     * @param string $subType
     */
    public function withPassport($subType = '')
    {
        return $this->withAnchorByValue(WantedAnchor::VALUE_PASSPORT, $subType);
    }

    /**
     * @param string $subType
     */
    public function withDrivingLicence($subType = '')
    {
        return $this->withAnchorByValue(WantedAnchor::VALUE_DRIVING_LICENSE, $subType);
    }

    /**
     * @param string $subType
     */
    public function withNationalId($subType = '')
    {
        return $this->withAnchorByValue(WantedAnchor::VALUE_NATIONAL_ID, $subType);
    }

    /**
     * @param string $subType
     */
    public function withPasscard($subType = '')
    {
        return $this->withAnchorByValue(WantedAnchor::VALUE_PASSCARD, $subType);
    }

    /**
     * @returns {SourceConstraint}
     */
    public function build()
    {
        return new SourceConstraint($this->anchors, $this->softPreference);
    }
}
