<?php
namespace YotiSandbox\Http;

use Yoti\Entity\Profile;
use YotiSandbox\Entity\SandboxAgeVerification;
use YotiSandbox\Entity\SandboxAnchor;
use YotiSandbox\Entity\SandboxAttribute;

class RequestBuilder
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
    public function includeRememberMeId($value)
    {
        $this->rememberMeId = $value;
    }

    public function includeFullName($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_FULL_NAME,
            $value,
            '',
            $optional,
            $anchors
        ));
    }

    public function includeFamilyName($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_FAMILY_NAME,
            $value,
            '',
            $optional,
            $anchors
        ));
    }

    public function includeGivenNames($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_GIVEN_NAMES,
            $value,
            '',
            $optional,
            $anchors
        ));
    }

    public function includeDateOfBirth($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_DATE_OF_BIRTH,
            $value,
            '',
            $optional,
            $anchors
        ));
    }

    public function includeGender($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_GENDER,
            $value,
            '',
            $optional,
            $anchors
        ));
    }

    public function includeNationality($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_NATIONALITY,
            $value,
            '',
            $optional,
            $anchors
        ));
    }

    public function includePhoneNumber($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_PHONE_NUMBER,
            $value,
            '',
            $optional,
            $anchors
        ));
    }

    public function includeSelfie($value, $optional = 'false', array $anchors = [])
    {
        $base64Selfie = base64_encode($value);
        $this->includeBase64Selfie($base64Selfie, $optional, $anchors);
    }

    public function includeBase64Selfie($value, $optional = 'true', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_SELFIE,
            $value,
            '',
            $optional,
            $anchors
        ));
    }

    public function includeEmailAddress($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_EMAIL_ADDRESS,
            $value,
            '',
            $optional,
            $anchors
        ));
    }

    public function includePostalAddress($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_POSTAL_ADDRESS,
            $value,
            '',
            $optional,
            $anchors
        ));
    }

    public function includeStructuredPostalAddress($value, $optional = 'false', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_STRUCTURED_POSTAL_ADDRESS,
            $value,
            '',
            $optional,
            $anchors
        ));
    }

    public function includeDocumentDetails($value, $optional = 'true', array $anchors = [])
    {
        $this->addAttribute($this->createAttribute(
            Profile::ATTR_DOCUMENT_DETAILS,
            $value,
            '',
            $optional,
            $anchors
        ));
    }

    public function includeAgeVerification(\DateTime $dateObj, $derivation, array $anchors = [])
    {
        $this->addAttribute(new SandboxAgeVerification(
            $dateObj,
            $derivation,
            $anchors
        ));
    }

    private function addAttribute(SandboxAttribute $attribute)
    {
        $this->sandboxAttributes[] = [
            'name' => $attribute->getName(),
            'value' => $attribute->getValue(),
            'derivation' => $attribute->getDerivation(),
            'optional' => $attribute->getOptional(),
            'anchors' => $this->formatAnchors($attribute->getAnchors())
        ];
    }

    private function formatAnchors(array $anchors)
    {
        $anchorsList = [];
        foreach ($anchors as $anchor) {
            /** @var SandboxAnchor $anchor */
            if (!($anchor instanceof SandboxAnchor)) {
                continue;
            }
            $anchorsList[] = [
                'type' => $anchor->getType(),
                'value' => $anchor->getValue(),
                'sub_type' => $anchor->getSubtype(),
                'timestamp' => $anchor->getTimestamp()
            ];
        }
        return $anchorsList;
    }

    private function createAttribute($name, $value, $derivation, $optional, array $anchors)
    {
        return new SandboxAttribute($name, $value, $derivation, $optional, $anchors);
    }

    /**
     * @return TokenRequest
     */
    public function getRequest()
    {
        return new TokenRequest($this->rememberMeId, $this->sandboxAttributes);
    }
}