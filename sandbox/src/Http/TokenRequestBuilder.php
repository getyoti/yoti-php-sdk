<?php

namespace YotiSandbox\Http;

use Yoti\Entity\Profile;
use YotiSandbox\Entity\SandboxAnchor;
use YotiSandbox\Entity\SandboxAttribute;
use YotiSandbox\Entity\SandboxAgeVerification;
use YotiSandbox\Entity\SandboxDocumentDetails;

class TokenRequestBuilder
{
    /**
     * @var string
     */
    private $rememberMeId;

    /**
     * @var array
     */
    private $sandboxAttributes = [];

    /**
     * @param string $value
     */
    public function setRememberMeId($value)
    {
        $this->rememberMeId = $value;
        return $this;
    }

    public function setFullName($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_FULL_NAME,
            $value,
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    public function setFamilyName($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_FAMILY_NAME,
            $value,
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    public function setGivenNames($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_GIVEN_NAMES,
            $value,
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    public function setDateOfBirth(\DateTime $dateTime, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_DATE_OF_BIRTH,
            $dateTime->format('Y-m-d'),
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    public function setGender($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_GENDER,
            $value,
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    public function setNationality($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_NATIONALITY,
            $value,
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    public function setPhoneNumber($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_PHONE_NUMBER,
            $value,
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    public function setSelfie($value, $optional = 'false', array $anchors = [])
    {
        $base64Selfie = base64_encode($value);
        return $this->setBase64Selfie($base64Selfie, $optional, $anchors);
    }

    public function setBase64Selfie($value, $optional = 'true', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_SELFIE,
            $value,
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    public function setEmailAddress($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_EMAIL_ADDRESS,
            $value,
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    public function setPostalAddress($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_POSTAL_ADDRESS,
            $value,
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    public function setStructuredPostalAddress($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_STRUCTURED_POSTAL_ADDRESS,
            $value,
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    public function setDocumentDetails(SandboxDocumentDetails $documentDetails, $optional = 'true', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_DOCUMENT_DETAILS,
            $documentDetails->getValue(),
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    public function setDocumentDetailsWithString($value, $optional = 'true', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_DOCUMENT_DETAILS,
            $value,
            '',
            $optional,
            $anchors
        ));
        return $this;
    }

    public function setAgeVerification(SandboxAgeVerification $ageVerification)
    {
        $this->addAttribute($ageVerification);
        return $this;
    }

    public function addAttribute(SandboxAttribute $attribute)
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

    private function formatAnchors(array $anchors)
    {
        $anchorsList = [];
        $tsMultiplier = 1000000;
        foreach ($anchors as $anchor) {
            /** @var SandboxAnchor $anchor */
            if (!($anchor instanceof SandboxAnchor)) {
                continue;
            }
            $anchorsList[] = [
                'type' => strtoupper($anchor->getType()),
                'value' => $anchor->getValue(),
                'sub_type' => $anchor->getSubtype(),
                'timestamp' => (int) $anchor->getTimestamp() * $tsMultiplier
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
     * @param array $anchors
     *
     * @return SandboxAttribute
     */
    private function createAttribute($name, $value, $derivation, $optional, array $anchors)
    {
        return new SandboxAttribute($name, $value, $derivation, $optional, $anchors);
    }

    /**
     * @return \YotiSandbox\Http\TokenRequest
     */
    public function build()
    {
        return new TokenRequest($this->rememberMeId, $this->sandboxAttributes);
    }
}
