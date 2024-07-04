<?php

declare(strict_types=1);

namespace Yoti\ShareUrl\Policy;

use stdClass;
use Yoti\Util\Json;
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
     * @var bool
     */
    private $wantedRememberMe;

    /**
     * @var object|null
     */
    private $identityProfileRequirements;

    /**
     * @var object|null
     */
    private $advancedIdentityProfileRequirements;

    /**
     * @param \Yoti\ShareUrl\Policy\WantedAttribute[] $wantedAttributes
     *   Array of attributes to be requested.
     * @param int[] $wantedAuthTypes
     *   Auth types represents the authentication type to be used.
     * @param bool $wantedRememberMe
     * @param object $identityProfileRequirements
     * @param object $advancedIdentityProfileRequirements
     */
    public function __construct(
        array $wantedAttributes,
        array $wantedAuthTypes,
        bool $wantedRememberMe = false,
        $identityProfileRequirements = null,
        $advancedIdentityProfileRequirements = null
    ) {
        Validation::isArrayOfType($wantedAttributes, [WantedAttribute::class], 'wantedAttributes');
        $this->wantedAttributes = $wantedAttributes;

        Validation::isArrayOfIntegers($wantedAuthTypes, 'wantedAuthTypes');
        $this->wantedAuthTypes = $wantedAuthTypes;

        $this->wantedRememberMe = $wantedRememberMe;
        $this->identityProfileRequirements = $identityProfileRequirements;
        $this->advancedIdentityProfileRequirements = $advancedIdentityProfileRequirements;
    }

    /**
     * @inheritDoc
     *
     * @return stdClass
     */
    public function jsonSerialize(): stdClass
    {
        return (object)[
            'wanted' => $this->wantedAttributes,
            'wanted_auth_types' => $this->wantedAuthTypes,
            'wanted_remember_me' => $this->wantedRememberMe,
            'wanted_remember_me_optional' => false,
            'identity_profile_requirements' => $this->identityProfileRequirements,
            'advanced_identity_profile_requirements' => $this->advancedIdentityProfileRequirements,
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return Json::encode($this);
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
