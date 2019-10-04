<?php

namespace Yoti\ShareUrl\Policy;

use Yoti\Util\Validation;

/**
 * Defines the list of wanted attributes.
 */
class DynamicPolicy implements \JsonSerializable
{
    /**
     * @var \Yoti\ShareUrl\Policy\WantedAttribute[]
     */
    private $wantedAttributes;

    /**
     * @var int[]
     */
    private $wantedAuthTypes;

    /**
     * @var boolean
     */
    private $wantedRememberMe;

    /**
     * @param \Yoti\ShareUrl\Policy\WantedAttribute[] $wantedAttributes
     *   Array of attributes to be requested.
     * @param int[] $wantedAuthTypes
     *   Auth types represents the authentication type to be used.
     * @param boolean $wantedRememberMe
     */
    public function __construct(
        $wantedAttributes,
        $wantedAuthTypes,
        $wantedRememberMe = false
    ) {
        Validation::isArrayOfType($wantedAttributes, [WantedAttribute::class], 'wantedAttributes');
        $this->wantedAttributes = $wantedAttributes;

        if ($wantedAuthTypes) {
            Validation::isArrayOfIntegers($wantedAuthTypes, 'wantedAuthTypes');
            $this->wantedAuthTypes = $wantedAuthTypes;
        } else {
            $this->wantedAuthTypes = [];
        }

        Validation::isBoolean($wantedRememberMe, 'wantedRememberMe');
        $this->wantedRememberMe = $wantedRememberMe;
    }

    /**
     * @inheritDoc
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'wanted' => $this->wantedAttributes,
            'wanted_auth_types' => $this->wantedAuthTypes,
            'wanted_remember_me' => $this->wantedRememberMe,
            'wanted_remember_me_optional' => false,
        ];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this);
    }
}
