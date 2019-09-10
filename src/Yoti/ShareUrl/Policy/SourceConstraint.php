<?php

namespace Yoti\ShareUrl\Policy;

class SourceConstraint implements \JsonSerializable
{
    const TYPE = 'SOURCE';

    /**
     * @var \Yoti\ShareUrl\Policy\WantedAnchor[]
     */
    private $anchors = [];

    /**
     * @var boolean
     */
    private $softPreference;

    /**
     * @param \Yoti\ShareUrl\Policy\WantedAnchor[] $anchors
     * @param boolean $softPreference
     */
    public function __construct($anchors, $softPreference = false)
    {
        $this->anchors = $anchors;
        $this->softPreference = $softPreference;
    }

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'type' => self::TYPE,
            'preferred_sources' => [
                'anchors' => $this->anchors,
                'soft_preference' => $this->softPreference,
            ],
        ];
    }
}
