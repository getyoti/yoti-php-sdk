<?php

declare(strict_types=1);

namespace Yoti\Sandbox\Profile\Request;

use Yoti\Profile\UserProfile;
use Yoti\Sandbox\Profile\Request\Attribute\SandboxAgeVerification;
use Yoti\Sandbox\Profile\Request\Attribute\SandboxAttribute;
use Yoti\Sandbox\Profile\Request\Attribute\SandboxDocumentDetails;

class TokenRequestBuilder
{
    /**
     * @var string
     */
    private $rememberMeId;

    /**
     * @var SandboxAttribute[]
     */
    private $sandboxAttributes = [];

    /**
     * @param string $value
     */
    public function setRememberMeId($value): self
    {
        $this->rememberMeId = $value;
        return $this;
    }

    /**
     * @param string $value
     * @param bool $optional
     * @param \Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setFullName(string $value, bool $optional = false, array $anchors = []): self
    {
        $this->addAttribute($this->createAttribute(
            UserProfile::ATTR_FULL_NAME,
            $value,
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    /**
     * @param string $value
     * @param bool $optional
     * @param \Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setFamilyName(string $value, bool $optional = false, array $anchors = []): self
    {
        $this->addAttribute($this->createAttribute(
            UserProfile::ATTR_FAMILY_NAME,
            $value,
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    /**
     * @param string $value
     * @param bool $optional
     * @param \Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setGivenNames(string $value, bool $optional = false, array $anchors = []): self
    {
        $this->addAttribute($this->createAttribute(
            UserProfile::ATTR_GIVEN_NAMES,
            $value,
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    /**
     * @param \DateTime $dateTime
     * @param bool $optional
     * @param \Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setDateOfBirth(\DateTime $dateTime, bool $optional = false, array $anchors = []): self
    {
        $this->addAttribute($this->createAttribute(
            UserProfile::ATTR_DATE_OF_BIRTH,
            $dateTime->format('Y-m-d'),
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    /**
     * @param string $value
     * @param bool $optional
     * @param \Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setGender(string $value, bool $optional = false, array $anchors = []): self
    {
        $this->addAttribute($this->createAttribute(
            UserProfile::ATTR_GENDER,
            $value,
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    /**
     * @param string $value
     * @param bool $optional
     * @param \Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setNationality(string $value, bool $optional = false, array $anchors = []): self
    {
        $this->addAttribute($this->createAttribute(
            UserProfile::ATTR_NATIONALITY,
            $value,
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    /**
     * @param string $value
     * @param bool $optional
     * @param \Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setPhoneNumber(string $value, bool $optional = false, array $anchors = []): self
    {
        $this->addAttribute($this->createAttribute(
            UserProfile::ATTR_PHONE_NUMBER,
            $value,
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    /**
     * @param string $value
     * @param bool $optional
     * @param \Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setSelfie(string $value, bool $optional = false, array $anchors = []): self
    {
        $base64Selfie = base64_encode($value);
        return $this->setBase64Selfie($base64Selfie, $optional, $anchors);
    }

    /**
     * @param string $value
     * @param bool $optional
     * @param \Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setBase64Selfie(string $value, bool $optional = false, array $anchors = []): self
    {
        $this->addAttribute($this->createAttribute(
            UserProfile::ATTR_SELFIE,
            $value,
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    /**
     * @param string $value
     * @param bool $optional
     * @param \Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setEmailAddress(string $value, bool $optional = false, array $anchors = []): self
    {
        $this->addAttribute($this->createAttribute(
            UserProfile::ATTR_EMAIL_ADDRESS,
            $value,
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    /**
     * @param string $value
     * @param bool $optional
     * @param \Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setPostalAddress(string $value, bool $optional = false, array $anchors = []): self
    {
        $this->addAttribute($this->createAttribute(
            UserProfile::ATTR_POSTAL_ADDRESS,
            $value,
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    /**
     * @param string $value
     * @param bool $optional
     * @param \Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setStructuredPostalAddress(string $value, bool $optional = false, array $anchors = []): self
    {
        $this->addAttribute($this->createAttribute(
            UserProfile::ATTR_STRUCTURED_POSTAL_ADDRESS,
            $value,
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    /**
     * @param \Yoti\Sandbox\Profile\Request\Attribute\SandboxDocumentDetails $documentDetails
     * @param bool $optional
     * @param \Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setDocumentDetails(
        SandboxDocumentDetails $documentDetails,
        bool $optional = true,
        array $anchors = []
    ): self {
        $this->addAttribute($this->createAttribute(
            UserProfile::ATTR_DOCUMENT_DETAILS,
            $documentDetails->getValue(),
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    /**
     * @param string $value
     * @param bool $optional
     * @param \Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setDocumentDetailsWithString(string $value, bool $optional = true, array $anchors = []): self
    {
        $this->addAttribute($this->createAttribute(
            UserProfile::ATTR_DOCUMENT_DETAILS,
            $value,
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    public function setAgeVerification(SandboxAgeVerification $ageVerification): self
    {
        $this->addAttribute($ageVerification);
        return $this;
    }

    public function addAttribute(SandboxAttribute $attribute): self
    {
        $this->sandboxAttributes[] = $attribute;
        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $derivation
     *  Empty value means there is no derivation for this attribute
     * @param bool $optional
     *  false value means this attribute is required
     * @param \Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor[] $anchors
     *
     * @return SandboxAttribute
     */
    private function createAttribute(
        string $name,
        string $value,
        string $derivation,
        bool $optional,
        array $anchors
    ): SandboxAttribute {
        return new SandboxAttribute($name, $value, $derivation, $optional, $anchors);
    }

    /**
     * @return \Yoti\Sandbox\Profile\Request\TokenRequest
     */
    public function build(): TokenRequest
    {
        return new TokenRequest($this->rememberMeId, $this->sandboxAttributes);
    }
}
