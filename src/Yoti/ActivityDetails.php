<?php
namespace Yoti;

use Yoti\Entity\Profile;
use Yoti\Entity\Receipt;
use Attrpubapi_v1\AttributeList;
use Yoti\Util\Age\Processor as AgeProcessor;
use Yoti\Entity\ApplicationProfile;
use Yoti\Util\Profile\AttributeListConverter;

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
     * @var array
     */
    private $oldProfileData = [];

    /**
     * @var \Yoti\Entity\Profile
     */
    private $userProfile;

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
     * ActivityDetails constructor.
     *
     * @param array $attributes
     * @param $rememberMeId
     */
    public function __construct(Receipt $receipt, $pem)
    {
        $this->receipt = $receipt;
        $this->pem = $pem;

        $this->setRememberMeId();
        $this->setProfile();
        $this->setApplicationProfile();
    }

    private function setRememberMeId()
    {
        $this->rememberMeId = $this->receipt->getRememberMeId();
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
        $this->addAgeVerifications($attributesMap);

        return $attributesMap;
    }

    /**
     * Add age_verifications data to the attributesMap
     *
     * @param array $attributesMap
     */
    private function addAgeVerifications(array &$attributesMap)
    {
        $ageProcessor = new AgeProcessor($attributesMap);
        if ($ageVerifications = $ageProcessor->findAgeVerifications())
        {
            $attributesMap[Profile::ATTR_AGE_VERIFICATIONS] = $ageVerifications;
        }
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
     * Get user id.
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->rememberMeId;
    }
}