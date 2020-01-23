<?php

declare(strict_types=1);

namespace YotiSandbox\Http;

use Yoti\Profile\UserProfile;
use YotiSandbox\Entity\SandboxAgeVerification;
use YotiSandbox\Entity\SandboxAttribute;
use YotiSandbox\Entity\SandboxDocumentDetails;

class TokenRequestBuilder
{
    /**
     * @var string
     */
    private $rememberMeId;

    /**
     * @var array[]
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
     * @param string $optional
     * @param \YotiSandbox\Entity\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setFullName(string $value, string $optional = 'false', array $anchors = []): self
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
     * @param string $optional
     * @param \YotiSandbox\Entity\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setFamilyName(string $value, string $optional = 'false', array $anchors = []): self
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
     * @param string $optional
     * @param \YotiSandbox\Entity\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setGivenNames(string $value, string $optional = 'false', array $anchors = []): self
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
     * @param string $optional
     * @param \YotiSandbox\Entity\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setDateOfBirth(\DateTime $dateTime, string $optional = 'false', array $anchors = []): self
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
     * @param string $optional
     * @param \YotiSandbox\Entity\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setGender(string $value, string $optional = 'false', array $anchors = []): self
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
     * @param string $optional
     * @param \YotiSandbox\Entity\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setNationality(string $value, string $optional = 'false', array $anchors = []): self
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
     * @param string $optional
     * @param \YotiSandbox\Entity\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setPhoneNumber(string $value, string $optional = 'false', array $anchors = []): self
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
     * @param string $optional
     * @param \YotiSandbox\Entity\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setSelfie(string $value, string $optional = 'false', array $anchors = []): self
    {
        $base64Selfie = base64_encode($value);
        return $this->setBase64Selfie($base64Selfie, $optional, $anchors);
    }

    /**
     * @param string $value
     * @param string $optional
     * @param \YotiSandbox\Entity\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setBase64Selfie(string $value, string $optional = 'true', array $anchors = []): self
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
     * @param string $optional
     * @param \YotiSandbox\Entity\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setEmailAddress(string $value, string $optional = 'false', array $anchors = []): self
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
     * @param string $optional
     * @param \YotiSandbox\Entity\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setPostalAddress(string $value, string $optional = 'false', array $anchors = []): self
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
     * @param string $optional
     * @param \YotiSandbox\Entity\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setStructuredPostalAddress(string $value, string $optional = 'false', array $anchors = []): self
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
     * @param \YotiSandbox\Entity\SandboxDocumentDetails $documentDetails
     * @param string $optional
     * @param \YotiSandbox\Entity\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setDocumentDetails(
        SandboxDocumentDetails $documentDetails,
        string $optional = 'true',
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
     * @param string $optional
     * @param \YotiSandbox\Entity\SandboxAnchor[] $anchors
     *
     * @return $this
     */
    public function setDocumentDetailsWithString(string $value, string $optional = 'true', array $anchors = []): self
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
        $this->sandboxAttributes[] = [
            'name' => $attribute->getName(),
            'value' => $attribute->getValue(),
            'derivation' => $attribute->getDerivation(),
            'optional' => $attribute->getOptional(),
            'anchors' => $this->formatAnchors($attribute->getAnchors())
        ];
        return $this;
    }

    /**
     * @param \YotiSandbox\Entity\SandboxAnchor[] $anchors
     *
     * @return array[]
     */
    private function formatAnchors(array $anchors): array
    {
        $anchorsList = [];
        $tsMultiplier = 1000000;
        foreach ($anchors as $anchor) {
            $anchorsList[] = [
                'type' => strtoupper($anchor->getType()),
                'value' => $anchor->getValue(),
                'sub_type' => $anchor->getSubtype(),
                'timestamp' => $anchor->getTimestamp() * $tsMultiplier
            ];
        }
        return $anchorsList;
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $derivation
     *  Empty value means there is no derivation for this attribute
     * @param string $optional
     *  'false' value means this attribute is required
     * @param \YotiSandbox\Entity\SandboxAnchor[] $anchors
     *
     * @return SandboxAttribute
     */
    private function createAttribute(
        string $name,
        string $value,
        string $derivation,
        string $optional,
        array $anchors
    ): SandboxAttribute {
        return new SandboxAttribute($name, $value, $derivation, $optional, $anchors);
    }

    /**
     * @return \YotiSandbox\Http\TokenRequest
     */
    public function build(): TokenRequest
    {
        return new TokenRequest($this->rememberMeId, $this->sandboxAttributes);
    }
}
