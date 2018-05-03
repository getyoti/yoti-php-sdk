<?php
namespace Yoti;

use Yoti\Entity\Selfie;
use Yoti\Entity\Profile;
use Yoti\Entity\Attribute;
use attrpubapi_v1\AttributeList;
use Yoti\Helper\ActivityDetailsHelper;

use Yoti\Util\Age\AgeUnderOverProcessor;
use Yoti\Util\Profile\AnchorProcessor;

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
        // the constructor is called from self::constructFromAttributeList
        $this->setProfile(new Profile([]));

        $this->helper = new ActivityDetailsHelper($this);
    }

    /**
     * Construct model from attributelist.
     *
     * @param AttributeList $attributeList
     * @param int $rememberMeId
     *
     * @return \Yoti\ActivityDetails
     */
    public static function constructFromAttributeList(AttributeList $attributeList, $rememberMeId)
    {
        // For ActivityDetails attributes
        $attrs = [];
        // For Profile attributes
        $profileAttributes = [];

        $anchorProcessor = new AnchorProcessor();

        foreach ($attributeList->getAttributesList() as $item) /** @var Attribute $item */
        {
            if ($item->getName() === 'selfie')
            {
                $attrs[$item->getName()] = new Selfie(
                    $item->getValue()->getContents(),
                    $item->getContentType()->name()
                );
            }
            else {
                $attrs[$item->getName()] = $item->getValue()->getContents();
            }

            $attributeAnchors = $anchorProcessor->process($item->getAnchorsList());

            $attrName = $item->getName();
            $attribute = new Attribute(
                $attrName,
                $item->getValue()->getContents(),
                $attributeAnchors['sources'],
                $attributeAnchors['verifiers']
            );
            $profileAttributes[$attrName] = $attribute;

            // Add age verification attributes
            if (preg_match(AgeUnderOverProcessor::AGE_PATTERN, $attrName))
            {
                $isAgeVerifiedAttr = new Attribute(
                    Attribute::IS_AGE_VERIFIED,
                    NULL,
                    $attributeAnchors['sources'],
                    $attributeAnchors['verifiers']
                );
                $profileAttributes[Attribute::IS_AGE_VERIFIED] = $isAgeVerifiedAttr;

                $verifiedAgeAttr = clone $isAgeVerifiedAttr;
                $verifiedAgeAttr->setName(Attribute::VERIFIED_AGE);
                $profileAttributes[Attribute::VERIFIED_AGE] = $verifiedAgeAttr;
            }
        }

        $inst = new self($attrs, $rememberMeId);
        // Add age verification attributes values if applicable
        if (isset($profileAttributes[Attribute::IS_AGE_VERIFIED]))
        {
            $profileAttributes[Attribute::IS_AGE_VERIFIED]->setValue($inst->isAgeVerified());
            $profileAttributes[Attribute::VERIFIED_AGE]->setValue($inst->getVerifiedAge());
        }
        $inst->setProfile(new Profile($profileAttributes));

        return $inst;
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
    public function setProfile(Profile $profile)
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
     * @return null|string
     */
    public function getFamilyName()
    {
        return $this->getProfileAttribute(self::ATTR_FAMILY_NAME);
    }

    /**
     * Get given names.
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
     * @return null|string
     */
    public function getFullName()
    {
        return $this->getProfileAttribute(self::ATTR_FULL_NAME);
    }

    /**
     * Get date of birth.
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
     * @return null|string
     */
    public function getGender()
    {
        return $this->getProfileAttribute(self::ATTR_GENDER);
    }

    /**
     * Get user nationality.
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
     * @return null|string
     */
    public function getPhoneNumber()
    {
        return $this->getProfileAttribute(self::ATTR_PHONE_NUMBER);
    }

    /**
     * Get user selfie image data.
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
     * @return null|string
     */
    public function getEmailAddress()
    {
        return $this->getProfileAttribute(self::ATTR_EMAIL_ADDRESS);
    }

    /**
     * Get user address.
     *
     * @return null|string
     */
    public function getPostalAddress()
    {
        return $this->getProfileAttribute(self::ATTR_POSTAL_ADDRESS);
    }

    /**
     * Get user structured postal address as an array.
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
     * @return bool|null
     */
    public function isAgeVerified()
    {
        return $this->helper->ageCondition->isVerified();
    }

    /**
     * @return null|string
     */
    public function getVerifiedAge()
    {
        return $this->helper->ageCondition->getVerifiedAge();
    }
}