<?php

namespace Yoti\Identity\Policy;

use stdClass;
use Yoti\Util\Validation;

class Policy implements \JsonSerializable
{
    /**
     * @var WantedAttribute[]
     */
    private array $wantedAttributes;

    /**
     * @var int[]
     */
    private array $wantedAuthTypes;

    private bool $wantedRememberMe;

    private bool $wantedRememberMeOptional;

    /**
     * @var object|null
     */
    private $identityProfileRequirements;

    /**
     * @var object|null
     */
    private $advancedIdentityProfileRequirements;

    /**
     * @param WantedAttribute[] $wantedAttributes
     *   Array of attributes to be requested.
     * @param int[] $wantedAuthTypes
     *   Auth types represents the authentication type to be used.
     * @param object $identityProfileRequirements
     * @param object $advancedidentityProfileRequirements
     */
    public function __construct(
        array $wantedAttributes,
        array $wantedAuthTypes,
        bool $wantedRememberMe = false,
        bool $wantedRememberMeOptional = false,
        $identityProfileRequirements = null,
        $advancedIdentityProfileRequirements = null
    ) {
        Validation::isArrayOfType($wantedAttributes, [WantedAttribute::class], 'wantedAttributes');
        $this->wantedAttributes = $wantedAttributes;

        Validation::isArrayOfIntegers($wantedAuthTypes, 'wantedAuthTypes');
        $this->wantedAuthTypes = $wantedAuthTypes;

        $this->wantedRememberMe = $wantedRememberMe;
        $this->wantedRememberMeOptional = $wantedRememberMeOptional;
        $this->identityProfileRequirements = $identityProfileRequirements;
        $this->advancedIdentityProfileRequirements = $advancedIdentityProfileRequirements;
    }


    public function jsonSerialize(): stdClass
    {
        return (object)[
            'wanted' => $this->wantedAttributes,
            'wanted_auth_types' => $this->wantedAuthTypes,
            'wanted_remember_me' => $this->wantedRememberMe,
            'wanted_remember_me_optional' => $this->wantedRememberMeOptional,
            'identity_profile_requirements' => $this->identityProfileRequirements,
            'advanced_identity_profile_requirements' => $this->advancedIdentityProfileRequirements,
        ];
    }

    /**
     * IdentityProfileRequirements requested in the policy
     *
     * @return object|null
     */
    public function getIdentityProfileRequirements()
    {
        return $this->identityProfileRequirements;
    }

    /**
     * AdvancedIdentityProfileRequirements requested in the policy
     *
     * @return object|null
     */
    public function getAdvancedIdentityProfileRequirements()
    {
        return $this->advancedIdentityProfileRequirements;
    }
}
