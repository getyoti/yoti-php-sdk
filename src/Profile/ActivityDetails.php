<?php

namespace Yoti\Profile;

use Yoti\Profile\Util\Age\AgeVerificationConverter;
use Yoti\Profile\Util\Attribute\AttributeListConverter;
use Yoti\Protobuf\Attrpubapi\AttributeList;
use Yoti\Util\DateTime;

/**
 * Class ActivityDetails
 *
 * @package Yoti
 * @author Yoti SDK <websdk@yoti.com>
 */
class ActivityDetails
{
    /**
     * @var string receipt identifier
     */
    private $rememberMeId;

    /**
     * @var string parent receipt identifier
     */
    private $parentRememberMeId;

    /**
     * @var \Yoti\Profile\Profile
     */
    private $userProfile;

    /**
     * @var \DateTime|null
     */
    private $timestamp;

    /**
     * @var ApplicationProfile
     */
    private $applicationProfile;

    /**
     * @var Receipt
     */
    private $receipt;

    /**
     * @var string
     */
    private $pem;

    /**
     * @var \Yoti\Profile\ExtraData\ExtraData
     */
    private $extraData;

    /**
     * ActivityDetails constructor.
     *
     * @param array $attributes
     * @param $rememberMeId
     */
    public function __construct(Receipt $receipt, $pem)
    {
        $this->receipt = $receipt;
        $this->pem = $pem;

        $this->setProfile();
        $this->setTimestamp();
        $this->setRememberMeId();
        $this->setParentRememberMeId();
        $this->setApplicationProfile();
        $this->setExtraData();
    }

    private function setRememberMeId()
    {
        $this->rememberMeId = $this->receipt->getRememberMeId();
    }

    private function setParentRememberMeId()
    {
        $this->parentRememberMeId = $this->receipt->getParentRememberMeId();
    }

    private function setTimestamp()
    {
        try {
            $timestamp = $this->receipt->getTimestamp();
            $this->timestamp = DateTime::stringToDateTime($timestamp);
        } catch (\Exception $e) {
            $this->timestamp = null;
            error_log("Warning: {$e->getMessage()}", 0);
        }
    }

    private function setProfile()
    {
        $protobufAttrList = $this->receipt->parseAttribute(
            Receipt::ATTR_OTHER_PARTY_PROFILE_CONTENT,
            $this->pem
        );
        $this->userProfile = new Profile($this->processUserProfileAttributes($protobufAttrList));
    }

    private function setApplicationProfile()
    {
        $protobufAttributesList = $this->receipt->parseAttribute(
            Receipt::ATTR_PROFILE_CONTENT,
            $this->pem
        );
        $this->applicationProfile = new ApplicationProfile(
            AttributeListConverter::convertToYotiAttributesMap($protobufAttributesList)
        );
    }

    private function processUserProfileAttributes(AttributeList $protobufAttributesList)
    {
        $attributesMap = AttributeListConverter::convertToYotiAttributesMap($protobufAttributesList);
        $this->appendAgeVerifications($attributesMap);

        return $attributesMap;
    }

    /**
     * Add age_verifications data to the attributesMap
     *
     * @param array $attributesMap
     */
    private function appendAgeVerifications(array &$attributesMap)
    {
        $ageVerificationConverter = new AgeVerificationConverter($attributesMap);
        $ageVerifications = $ageVerificationConverter->getAgeVerificationsFromAttrsMap();
        $attributesMap[Profile::ATTR_AGE_VERIFICATIONS] = $ageVerifications;
    }

    /**
     * @return string|null
     */
    public function getReceiptId()
    {
        return $this->receipt->getReceiptId();
    }

    /**
     * @return \DateTime|null
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return ApplicationProfile
     */
    public function getApplicationProfile()
    {
        return $this->applicationProfile;
    }

    /**
     * Get user profile object.
     *
     * @return Profile
     */
    public function getProfile()
    {
        return $this->userProfile;
    }

    /**
     * Get rememberMeId.
     *
     * @return null|string
     */
    public function getRememberMeId()
    {
        return $this->rememberMeId;
    }

    /**
     * Get Parent Remember Me Id.
     *
     * @return null|string
     */
    public function getParentRememberMeId()
    {
        return $this->parentRememberMeId;
    }

    /**
     * Set extra data from receipt.
     */
    private function setExtraData()
    {
        $this->extraData = $this->receipt->parseExtraData($this->pem);
    }

    /**
     * @return \Yoti\Profile\ExtraData\ExtraData
     */
    public function getExtraData()
    {
        return $this->extraData;
    }
}
