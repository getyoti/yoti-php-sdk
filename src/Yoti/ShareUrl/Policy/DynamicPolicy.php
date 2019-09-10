<?php

namespace Yoti\ShareUrl\Policy;

use Yoti\Util\Validation;

/**
 * Defines the list of wanted attributes.
 */
class DynamicPolicy implements \JsonSerializable
{
    /**
     * @param \Yoti\ShareUrl\Policy\WantedAttribute[] $wantedAttributes
     *   Array of attributes to be requested.
     * @param integer[] $wantedAuthTypes
     *   Auth types represents the authentication type to be used.
     * @param boolean $wantedRememberMe
     */
    public function __construct(
        $wantedAttributes,
        $wantedAuthTypes,
        $wantedRememberMe = false
    ) {
        foreach ($wantedAttributes as $wantedAttribute) {
            if (!$wantedAttribute instanceof WantedAttribute) {
                throw new \TypeError(sprintf('All wanted attributes must be instance of WantedAttribute'));
            }
        }
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
     * @return \Yoti\ShareUrl\Policy\WantedAttribute[]
     *   Array of attributes to be requested.
     */
    public function getWantedAttributes()
    {
        return $this->wantedAttributes;
    }

    /**
     * @return integer[]
     *   Auth types represents the authentication type to be used.
     */
    public function getWantedAuthTypes()
    {
        return $this->wantedAuthTypes;
    }

    /**
     * @return boolean
     */
    public function getWantedRememberMe()
    {
        return $this->wantedRememberMe;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'wanted' => $this->getWantedAttributes(),
            'wanted_auth_types' => $this->getWantedAuthTypes(),
            'wanted_remember_me' => $this->getWantedRememberMe(),
            'wanted_remember_me_optional' => false,
        ];
    }
}
