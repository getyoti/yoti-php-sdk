<?php
namespace Yoti;

use Yoti\Entity\Selfie;
use Yoti\Entity\Profile;
use Yoti\Entity\Attribute;
use Attrpubapi_v1\Attribute as ProtobufAttribute;
use Attrpubapi_v1\AttributeList;
use Yoti\Helper\ActivityDetailsHelper;
use Yoti\Util\Age\AgeUnderOverProcessor;
use Yoti\Util\Profile\AnchorProcessor;
use Yoti\Util\Profile\AttributeConverter;
use Yoti\Entity\ApplicationProfile;

/**
 * Class ActivityDetails
 *
 * @package Yoti
 * @author Yoti SDK <websdk@yoti.com>
 */
class ActivityDetails
{
    const ATTR_FAMILY_NAME = 'family_name';
    const ATTR_GIVEN_NAMES = 'given_names';
    const ATTR_FULL_NAME = 'full_name';
    const ATTR_DATE_OF_BIRTH = 'date_of_birth';
    const ATTR_AGE_VERIFIED = 'age_verified';
    const ATTR_GENDER = 'gender';
    const ATTR_NATIONALITY = 'nationality';
    const ATTR_PHONE_NUMBER = 'phone_number';
    const ATTR_SELFIE = 'selfie';
    const ATTR_EMAIL_ADDRESS = 'email_address';
    const ATTR_POSTAL_ADDRESS = 'postal_address';
    const ATTR_STRUCTURED_POSTAL_ADDRESS = 'structured_postal_address';

    /**
     * @var string receipt identifier
     */
    private $_rememberMeId;

    /**
     * @var array
     */
    private $_profile = [];

    /**
     * @var \Yoti\Entity\Profile
     */
    private $profile;

    /**
     * @var ApplicationProfile
     */
    private $applicationProfile;

    /**
     * @var ActivityDetailsHelper
     */
    public $helper;

    /**
     * ActivityDetails constructor.
     *
     * @param array $attributes
     * @param $rememberMeId
     */
    public function __construct(array $attributes, $rememberMeId)
    {
        $this->_rememberMeId = $rememberMeId;

        // Populate user profile attributes
        foreach ($attributes as $param => $value)
        {
            $this->setProfileAttribute($param, $value);
        }

        // Setting an empty profile here in case
        // the constructor is called directly
        $this->setProfile(new Profile([]));

        $this->helper = new ActivityDetailsHelper($this);
    }

    /**
     * Construct model from attributelist.
     *
     * @param AttributeList $attributeList
     * @param string $rememberMeId
     *
     * @return ActivityDetails
     */
    public static function constructFromAttributeList(AttributeList $attributeList, $rememberMeId)
    {
        // For ActivityDetails attributes
        $attrs = [];
        // For Profile attributes
        $profileAttributes = [];
        $ageConditionMetadata = [];
        $anchorProcessor = new AnchorProcessor();

        foreach ($attributeList->getAttributes() as $item) /** @var ProtobufAttribute $item */
        {
            $attrName = $item->getName();
            if ($attrName === 'selfie') {
                $attrs[$attrName] = new Selfie(
                    $item->getValue(),
                    $item->getName()
                );
            }
            else {
                $attrs[$attrName] = $item->getValue();
            }

            // Build attribute object for user profile
            $attributeAnchors = $anchorProcessor->process($item->getAnchors());
            $yotiAttribute = self::createYotiAttribute($item, $attributeAnchors, $attrName);
            $profileAttributes[$attrName] = $yotiAttribute;
            // Add 'is_age_verified' and 'verified_age' attributes
            if (NULL !==  $yotiAttribute && preg_match(AgeUnderOverProcessor::AGE_PATTERN, $attrName)) {
                $ageConditionMetadata['sources'] = $attributeAnchors['sources'];
                $ageConditionMetadata['verifiers'] = $attributeAnchors['verifiers'];
            }
        }

        $inst = new self($attrs, $rememberMeId); /** @var ActivityDetails $inst */
        // Add 'age_condition' and 'verified_age' attributes values
        if (!empty($ageConditionMetadata)) {
            $profileAttributes[Attribute::AGE_CONDITION] = new Attribute(
                Attribute::AGE_CONDITION,
                $inst->isAgeVerified(),
                $ageConditionMetadata['sources'],
                $ageConditionMetadata['verifiers']
            );

            $profileAttributes[Attribute::VERIFIED_AGE] = new Attribute(
                Attribute::VERIFIED_AGE,
                $inst->getVerifiedAge(),
                $ageConditionMetadata['sources'],
                $ageConditionMetadata['verifiers']
            );
        }
        $inst->setProfile(new Profile($profileAttributes));

        return $inst;
    }

    public function createApplicationProfile(AttributeList $attributeList)
    {
        $profileAttributes = [];
        $anchorProcessor = new AnchorProcessor();

        foreach($attributeList->getAttributes() as $attr) { /** @var ProtobufAttribute $attr */
            $attrName = $attr->getName();
            $attrValue = AttributeConverter::convertValueBasedOnAttributeName($attr);
            $attributeAnchors = $anchorProcessor->process($attr->getAnchors());
            $profileAttributes[$attr->getName()] = new Attribute(
                $attrName,
                $attrValue,
                $attributeAnchors['sources'],
                $attributeAnchors['verifiers']
            );
        }
        $this->applicationProfile = new ApplicationProfile($profileAttributes);
    }

    /**
     * @return ApplicationProfile
     */
    public function getApplicationProfile()
    {
        return $this->applicationProfile;
    }

    /**
     * @param ProtobufAttribute $protobufAttribute
     * @param array $attributeAnchors
     * @param $attrName
     *
     * @return null|Attribute
     */
    private static function createYotiAttribute(ProtobufAttribute $protobufAttribute, array $attributeAnchors, $attrName)
    {
        try {
            $attrValue = AttributeConverter::convertValueBasedOnAttributeName($protobufAttribute);
            $yotiAttribute = new Attribute(
                $attrName,
                $attrValue,
                $attributeAnchors['sources'],
                $attributeAnchors['verifiers']
            );
        } catch (\Exception $e) {
            $yotiAttribute = NULL;
            trigger_error($e->getMessage(), E_USER_WARNING);
        }

        return $yotiAttribute;
    }

    /**
     * Set a user profile attribute.
     *
     * @param $param
     * @param $value
     */
    protected function setProfileAttribute($param, $value)
    {
        if (!empty($param)) {
            $this->_profile[$param] = $value;
        }
    }

    /**
     * Get user profile attribute.
     *
     * @param null|string $param
     *
     * @return array|mixed
     */
    public function getProfileAttribute($param = null)
    {
        if ($param)
        {
            return $this->hasProfileAttribute($param) ? $this->_profile[$param] : null;
        }

        return $this->_profile;
    }

    /**
     * @param Profile $profile
     */
    protected function setProfile(Profile $profile)
    {
        $this->profile = $profile;
    }

    /**
     * Get user profile object.
     *
     * @return Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Check if attribute exists.
     *
     * @param string $param
     *
     * @return bool
     */
    public function hasProfileAttribute($param)
    {
        return array_key_exists($param, $this->_profile);
    }

    /**
     * Get user id.
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->_rememberMeId;
    }

    /**
     * Get family name.
     *
     * @deprecated 1.2.0
     *  Use profile::getFamilyName()
     *
     * @return null|string
     */
    public function getFamilyName()
    {
        return $this->getProfileAttribute(self::ATTR_FAMILY_NAME);
    }

    /**
     * Get given names.
     *
     * @deprecated 1.2.0
     *  Use profile::getGivenNames()
     *
     * @return null|string
     */
    public function getGivenNames()
    {
        return $this->getProfileAttribute(self::ATTR_GIVEN_NAMES);
    }

    /**
     * Get full name.
     *
     * @deprecated 1.2.0
     *  Use profile::getFullName()
     *
     * @return null|string
     */
    public function getFullName()
    {
        return $this->getProfileAttribute(self::ATTR_FULL_NAME);
    }

    /**
     * Get date of birth.
     *
     * @deprecated 1.2.0
     *  Use profile::getDateOfBirth()
     *
     * @return null|string
     */
    public function getDateOfBirth()
    {
        return $this->getProfileAttribute(self::ATTR_DATE_OF_BIRTH);
    }

    /**
     * Get gender.
     *
     * @deprecated 1.2.0
     *  Use profile::getGender()
     *
     * @return null|string
     */
    public function getGender()
    {
        return $this->getProfileAttribute(self::ATTR_GENDER);
    }

    /**
     * Get user nationality.
     *
     * @deprecated 1.2.0
     *  Use profile::getNationality()
     *
     * @return null|string
     */
    public function getNationality()
    {
        return $this->getProfileAttribute(self::ATTR_NATIONALITY);
    }

    /**
     * Get user phone number.
     *
     * @deprecated 1.2.0
     *  Use profile::getPhoneNumber()
     *
     * @return null|string
     */
    public function getPhoneNumber()
    {
        return $this->getProfileAttribute(self::ATTR_PHONE_NUMBER);
    }

    /**
     * Get user selfie image data.
     *
     * @deprecated 1.2.0
     *  Use profile::getSelfie()
     *
     * @return null|string
     */
    public function getSelfie()
    {
        $selfie = $this->getProfileAttribute(self::ATTR_SELFIE);

        if($selfie instanceof Selfie)
        {
            $selfie = $selfie->getContent();
        }

        return $selfie;
    }

    /**
     * Get selfie image object.
     *
     * @deprecated 1.2.0
     *
     * @return null| \Yoti\Entity\Selfie $selfie
     */
    public function getSelfieEntity()
    {
        $selfieObj = $this->getProfileAttribute(self::ATTR_SELFIE);
        // Returns selfie entity or null
        return ($selfieObj instanceof Selfie) ? $selfieObj : NULL;
    }

    /**
     * Get user email address.
     *
     * @deprecated 1.2.0
     *  Use profile::getEmailAddress()
     *
     * @return null|string
     */
    public function getEmailAddress()
    {
        return $this->getProfileAttribute(self::ATTR_EMAIL_ADDRESS);
    }

    /**
     * Get user address.
     *
     * @deprecated 1.2.0
     *  Use profile::getPostalAddress()
     *
     * @return null|string
     */
    public function getPostalAddress()
    {
        $postalAddress = $this->getProfileAttribute(self::ATTR_POSTAL_ADDRESS);
        if (NULL === $postalAddress) {
            // Get it from structured_postal_address.formatted_address
            $structuredPostalAddress = $this->getStructuredPostalAddress();
            if (
                is_array($structuredPostalAddress)
                && isset($structuredPostalAddress['formatted_address'])
            ) {
                $postalAddress = $structuredPostalAddress['formatted_address'];
            }
        }
        return $postalAddress;
    }

    /**
     * Get user structured postal address as an array.
     *
     * @deprecated 1.2.0
     *  Use profile::getStructuredPostalAddress()
     *
     * @return null|array
     */
    public function getStructuredPostalAddress()
    {
        $structuredPostalAddress = $this->getProfileAttribute(self::ATTR_STRUCTURED_POSTAL_ADDRESS);
        return json_decode($structuredPostalAddress, true);
    }

    /**
     * Returns a boolean representing the attribute value
     * Or null if the attribute is not set in the dashboard
     *
     * @deprecated 1.2.0
     *  Use profile::getAgeCondition()
     *
     * @return bool|null
     */
    public function isAgeVerified()
    {
        return $this->helper->ageCondition->isVerified();
    }

    /**
     * @deprecated 1.2.0
     *  Use profile::getVerifiedAge()
     *
     * @return null|string
     */
    public function getVerifiedAge()
    {
        return $this->helper->ageCondition->getVerifiedAge();
    }
}