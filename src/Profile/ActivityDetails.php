<?php

declare(strict_types=1);

namespace Yoti\Profile;

use Yoti\Profile\ExtraData\ExtraData;
use Yoti\Profile\Util\Age\AgeVerificationConverter;
use Yoti\Profile\Util\Attribute\AttributeListConverter;
use Yoti\Protobuf\Attrpubapi\AttributeList;
use Yoti\Util\DateTime;
use Yoti\Util\PemFile;

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
     * @var \Yoti\Profile\Receipt
     */
    private $receipt;

    /**
     * @var \Yoti\Util\PemFile
     */
    private $pemFile;

    /**
     * @var \Yoti\Profile\ExtraData\ExtraData
     */
    private $extraData;

    /**
     * ActivityDetails constructor.
     *
     * @param \Yoti\Profile\Receipt $receipt
     * @param \Yoti\Util\PemFile $pemFile
     */
    public function __construct(Receipt $receipt, PemFile $pemFile)
    {
        $this->receipt = $receipt;
        $this->pemFile = $pemFile;

        $this->setProfile();
        $this->setTimestamp();
        $this->setRememberMeId();
        $this->setParentRememberMeId();
        $this->setApplicationProfile();
        $this->setExtraData();
    }

    private function setRememberMeId(): void
    {
        $this->rememberMeId = $this->receipt->getRememberMeId();
    }

    private function setParentRememberMeId(): void
    {
        $this->parentRememberMeId = $this->receipt->getParentRememberMeId();
    }

    private function setTimestamp(): void
    {
        try {
            $timestamp = $this->receipt->getTimestamp();
            $this->timestamp = DateTime::stringToDateTime($timestamp);
        } catch (\Exception $e) {
            $this->timestamp = null;
            error_log("Warning: {$e->getMessage()}", 0);
        }
    }

    private function setProfile(): void
    {
        $protobufAttrList = $this->receipt->parseAttribute(
            Receipt::ATTR_OTHER_PARTY_PROFILE_CONTENT,
            $this->pemFile
        );
        $this->userProfile = new Profile($this->processUserProfileAttributes($protobufAttrList));
    }

    private function setApplicationProfile(): void
    {
        $protobufAttributesList = $this->receipt->parseAttribute(
            Receipt::ATTR_PROFILE_CONTENT,
            $this->pemFile
        );
        $this->applicationProfile = new ApplicationProfile(
            AttributeListConverter::convertToYotiAttributesMap($protobufAttributesList)
        );
    }

    private function processUserProfileAttributes(AttributeList $protobufAttributesList): array
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
    private function appendAgeVerifications(array &$attributesMap): void
    {
        $ageVerificationConverter = new AgeVerificationConverter($attributesMap);
        $ageVerifications = $ageVerificationConverter->getAgeVerificationsFromAttrsMap();
        $attributesMap[Profile::ATTR_AGE_VERIFICATIONS] = $ageVerifications;
    }

    /**
     * @return string|null
     */
    public function getReceiptId(): ?string
    {
        return $this->receipt->getReceiptId();
    }

    /**
     * @return \DateTime|null
     */
    public function getTimestamp(): ?\DateTime
    {
        return $this->timestamp;
    }

    /**
     * @return ApplicationProfile
     */
    public function getApplicationProfile(): ApplicationProfile
    {
        return $this->applicationProfile;
    }

    /**
     * Get user profile object.
     *
     * @return Profile
     */
    public function getProfile(): Profile
    {
        return $this->userProfile;
    }

    /**
     * Get rememberMeId.
     *
     * @return null|string
     */
    public function getRememberMeId(): ?string
    {
        return $this->rememberMeId;
    }

    /**
     * Get Parent Remember Me Id.
     *
     * @return null|string
     */
    public function getParentRememberMeId(): ?string
    {
        return $this->parentRememberMeId;
    }

    /**
     * Set extra data from receipt.
     */
    private function setExtraData(): void
    {
        $this->extraData = $this->receipt->parseExtraData($this->pemFile);
    }

    /**
     * @return \Yoti\Profile\ExtraData\ExtraData
     */
    public function getExtraData(): ExtraData
    {
        return $this->extraData;
    }
}
