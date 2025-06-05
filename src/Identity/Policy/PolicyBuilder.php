<?php

namespace Yoti\Identity\Policy;

use Yoti\Identity\Constraint\Constraint;
use Yoti\Profile\UserProfile;
use Yoti\Util\Json;

class PolicyBuilder
{
    private const SELFIE_AUTH_TYPE = 1;

    private const PIN_AUTH_TYPE = 2;

    /**
     * @var WantedAttribute[]
     */
    private array $wantedAttributes = [];

    /**
     * @var int[]
     */
    private array $wantedAuthTypes = [];

    private bool $wantedRememberMe = false;

    private bool $wantedRememberMeOptional = false;

    private ?object $identityProfileRequirements = null;
    private ?object $advancedIdentityProfileRequirements = null;

    public function withWantedAttribute(WantedAttribute $wantedAttribute): self
    {
        $key = $wantedAttribute->getName();

        if (null !== $wantedAttribute->getDerivation()) {
            $key = $wantedAttribute->getDerivation();
        }

        if (null !== $wantedAttribute->getConstraints()) {
            $key .= '-' . hash('sha256', Json::encode($wantedAttribute->getConstraints()));
        }

        $this->wantedAttributes[$key] = $wantedAttribute;

        return $this;
    }

    /**
     * @param Constraint[]|null $constraints
     */
    public function withWantedAttributeByName(
        string $name,
        ?array $constraints = null,
        ?bool $acceptSelfAsserted = null
    ): self {
        $wantedAttributeBuilder = (new WantedAttributeBuilder())
            ->withName($name);

        if ($constraints !== null) {
            $wantedAttributeBuilder->withConstraints($constraints);
        }

        if ($acceptSelfAsserted !== null) {
            $wantedAttributeBuilder->withAcceptSelfAsserted($acceptSelfAsserted);
        }

        return $this->withWantedAttribute($wantedAttributeBuilder->build());
    }

    /**
     * @param Constraint[]|null $constraints
     */
    public function withFamilyName(?array $constraints = null, ?bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_FAMILY_NAME,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param Constraint[]|null $constraints
     */
    public function withGivenNames(?array $constraints = null, ?bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_GIVEN_NAMES,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param Constraint[]|null $constraints
     */
    public function withFullName(?array $constraints = null, ?bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_FULL_NAME,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param Constraint[]|null $constraints
     */
    public function withDateOfBirth(?array $constraints = null, ?bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_DATE_OF_BIRTH,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param Constraint[]|null $constraints
     */
    public function withAgeOver(int $age, ?array $constraints = null, ?bool $acceptSelfAsserted = null): self
    {
        return $this->withAgeDerivedAttribute(
            UserProfile::AGE_OVER . $age,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param Constraint[]|null $constraints
     */
    public function withAgeUnder(int $age, ?array $constraints = null, ?bool $acceptSelfAsserted = null): self
    {
        return $this->withAgeDerivedAttribute(
            UserProfile::AGE_UNDER . $age,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param Constraint[]|null $constraints
     */
    public function withAgeDerivedAttribute(
        string $derivation,
        ?array $constraints = null,
        ?bool $acceptSelfAsserted = null
    ): self {
        $wantedAttributeBuilder = (new WantedAttributeBuilder())
            ->withName(UserProfile::ATTR_DATE_OF_BIRTH)
            ->withDerivation($derivation);

        if ($constraints !== null) {
            $wantedAttributeBuilder->withConstraints($constraints);
        }

        if ($acceptSelfAsserted !== null) {
            $wantedAttributeBuilder->withAcceptSelfAsserted($acceptSelfAsserted);
        }

        return $this->withWantedAttribute($wantedAttributeBuilder->build());
    }

    /**
     * @param Constraint[]|null $constraints
     */
    public function withGender(?array $constraints = null, ?bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_GENDER,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param Constraint[]|null $constraints
     */
    public function withPostalAddress(?array $constraints = null, ?bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_POSTAL_ADDRESS,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param Constraint[]|null $constraints
     */
    public function withStructuredPostalAddress(?array $constraints = null, ?bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_STRUCTURED_POSTAL_ADDRESS,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param Constraint[]|null $constraints
     */
    public function withNationality(?array $constraints = null, ?bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_NATIONALITY,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param Constraint[]|null $constraints
     */
    public function withPhoneNumber(?array $constraints = null, ?bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_PHONE_NUMBER,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param Constraint[]|null $constraints
     */
    public function withSelfie(?array $constraints = null, ?bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_SELFIE,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param Constraint[]|null $constraints
     */
    public function withDocumentDetails(?array $constraints = null, ?bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_DOCUMENT_DETAILS,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param Constraint[]|null $constraints
     */
    public function withDocumentImages(?array $constraints = null, ?bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_DOCUMENT_IMAGES,
            $constraints,
            $acceptSelfAsserted
        );
    }

    /**
     * @param Constraint[]|null $constraints
     */
    public function withEmail(?array $constraints = null, ?bool $acceptSelfAsserted = null): self
    {
        return $this->withWantedAttributeByName(
            UserProfile::ATTR_EMAIL_ADDRESS,
            $constraints,
            $acceptSelfAsserted
        );
    }


    public function withSelfieAuthentication(bool $enabled = true): self
    {
        return $this->withWantedAuthType(self::SELFIE_AUTH_TYPE, $enabled);
    }


    public function withPinAuthentication(bool $enabled = true): self
    {
        return $this->withWantedAuthType(self::PIN_AUTH_TYPE, $enabled);
    }

    public function withWantedAuthType(int $wantedAuthType, bool $enabled = true): self
    {
        if ($enabled) {
            $this->wantedAuthTypes[$wantedAuthType] = $wantedAuthType;
        } else {
            unset($this->wantedAuthTypes[$wantedAuthType]);
        }

        return $this;
    }


    public function withWantedRememberMe(bool $wantedRememberMe): self
    {
        $this->wantedRememberMe = $wantedRememberMe;
        return $this;
    }

    public function withWantedRememberMeOptional(bool $wantedRememberMeOptional): self
    {
        $this->wantedRememberMeOptional = $wantedRememberMeOptional;
        return $this;
    }

    /**
     * Use an Identity Profile Requirement object for the share
     *
     * @param object $identityProfileRequirements
     * @return $this
     */
    public function withIdentityProfileRequirements($identityProfileRequirements): self
    {
        $this->identityProfileRequirements = $identityProfileRequirements;
        return $this;
    }

    /**
     * Use an Advanced Identity Profile Requirement object for the share
     *
     * @param object $advancedIdentityProfileRequirements
     * @return $this
     */
    public function withAdvancedIdentityProfileRequirements($advancedIdentityProfileRequirements): self
    {
        $this->advancedIdentityProfileRequirements = $advancedIdentityProfileRequirements;
        return $this;
    }

    public function build(): Policy
    {
        return new Policy(
            array_values($this->wantedAttributes),
            array_values($this->wantedAuthTypes),
            $this->wantedRememberMe,
            $this->wantedRememberMeOptional,
            $this->identityProfileRequirements,
            $this->advancedIdentityProfileRequirements
        );
    }
}
