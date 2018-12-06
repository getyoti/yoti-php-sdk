<?php
namespace YotiSandbox\Http;

use Yoti\Http\Payload;
use Yoti\Entity\Anchor;
use Yoti\Entity\Attribute;

class RequestBuilder
{
    private $sandboxAttributes = [];

    public function __construct()
    {

    }

    public function includeFullName($value, array $anchors)
    {
        $this->addAttribute(Attribute::FULL_NAME, $value, $anchors);
    }

    public function includeFamilyName($value, array $anchors)
    {
        $this->addAttribute(Attribute::FAMILY_NAME, $value, $anchors);
    }

    public function includeGivenNames($value, array $anchors)
    {
        $this->addAttribute(Attribute::GIVEN_NAMES, $value, $anchors);
    }

    public function includeDateOfBirth($value, array $anchors)
    {
        $this->addAttribute(Attribute::DATE_OF_BIRTH, $value, $anchors);
    }

    public function includeGender($value, array $anchors)
    {
        $this->addAttribute(Attribute::GENDER, $value, $anchors);
    }

    public function includeNationality($value, array $anchors)
    {
        $this->addAttribute(Attribute::NATIONALITY, $value, $anchors);
    }

    public function includePhoneNumber($value, array $anchors)
    {
        $this->addAttribute(Attribute::PHONE_NUMBER, $value, $anchors);
    }

    public function includeSelfie($value, array $anchors)
    {
        $this->addAttribute(Attribute::SELFIE, $value, $anchors);
    }

    public function includeEmailAddress($value, array $anchors)
    {
        $this->addAttribute(Attribute::EMAIL_ADDRESS, $value, $anchors);
    }

    public function includePostalAddress($value, array $anchors)
    {
        $this->addAttribute(Attribute::POSTAL_ADDRESS, $value, $anchors);
    }

    public function includeStructuredPostalAddress($value, array $anchors)
    {
        $this->addAttribute(
            Attribute::STRUCTURED_POSTAL_ADDRESS,
            $value,
            $anchors
        );
    }

    public function addAttribute($attrName, $attrValue, array $anchors)
    {
        $this->sandboxAttributes[] = [
            'name' => $attrName,
            'value' => $attrValue,
            'derivation' => '',
            'anchors' => $this->formatAnchors($anchors)
        ];
    }

    public function includeDocumentDetails($value, array $anchors)
    {
        $this->addAttribute(Attribute::DOCUMENT_DETAILS, $value, $anchors);
    }

    private function formatAnchors(array $anchors)
    {
        $anchorsList = [];
        foreach($anchors as $anchor) { /** @var Anchor $anchor */
            $anchorsList[] = [
                'type' => $anchor->getType(),
                'value' => $anchor->getValue(),
                'sub_type' => $anchor->getSubtype(),
                'timestamp' => $anchor->getSignedTimeStamp()->getTimestamp()->getTimestamp()
            ];
        }
        return $anchorsList;
    }

    public function getPayload()
    {
        return new Payload($this->sandboxAttributes);
    }
}