<?php
namespace Yoti;

use attrpubapi_v1\Attribute;
use attrpubapi_v1\AttributeList;

/**
 * Class ActivityDetails
 *
 * @package Yoti
 * @author Simon Tong <simon.tong@yoti.com>
 */
class ActivityDetails
{
    const ATTR_FAMILY_NAME = 'family_name';
    const ATTR_GIVEN_NAMES = 'given_names';
    const ATTR_FULL_NAME = 'full_name';
    const ATTR_DATE_OF_BIRTH = 'date_of_birth';
    const ATTR_GENDER = 'gender';
    const ATTR_NATIONALITY = 'nationality';
    const ATTR_PHONE_NUMBER = 'phone_number';
    const ATTR_SELFIE = 'selfie';
    const ATTR_EMAIL_ADDRESS = 'email_address';
    const ATTR_POSTAL_ADDRESS = 'postal_address';

    /**
     * @var string receipt identifier
     */
    private $_rememberMeId;

    /**
     * @var array
     */
    private $_profile = [];

    /**
     * ActivityDetails constructor.
     * @param array $attributes
     * @param $rememberMeId
     */
    public function __construct(array $attributes, $rememberMeId)
    {
        $this->_rememberMeId = $rememberMeId;

        // populate attributes
        foreach ($attributes as $param => $value)
        {
            $this->setProfileAttribute($param, $value);
        }
    }

    /**
     * Construct model from attributelist
     * @param AttributeList $attributeList
     * @param $rememberMeId
     * @return \Yoti\ActivityDetails
     */
    public static function constructFromAttributeList(AttributeList $attributeList, $rememberMeId)
    {
        $attrs = array();
        /**
         * @var Attribute $item
         */
        foreach ($attributeList->getAttributesList() as $item)
        {
            $attrs[$item->getName()] = $item->getValue()->getContents();
        }

        $inst = new self($attrs, $rememberMeId);

        return $inst;
    }

    /**
     * Set a user profile attribute
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
     * Get user profile attribute
     * @param null|string $param
     * @return array|mixed
     */
    public function getProfileAttribute($param = null)
    {
        if ($param)
        {
            return ($this->hasProfileAttribute($param)) ? $this->_profile[$param] : null;
        }

        return $this->_profile;
    }

    /**
     * @param $param
     * @return bool
     */
    public function hasProfileAttribute($param)
    {
        return array_key_exists($param, $this->_profile);
    }

    /**
     * Get user id
     * @return string
     */
    public function getUserId()
    {
        return $this->_rememberMeId;
    }

    /**
     * @return null|string
     */
    public function getFamilyName()
    {
        return $this->getProfileAttribute(self::ATTR_FAMILY_NAME);
    }

    /**
     * @return null|string
     */
    public function getGivenNames()
    {
        return $this->getProfileAttribute(self::ATTR_GIVEN_NAMES);
    }

    /**
     * @return null|string
     */
    public function getFullName()
    {
        return $this->getProfileAttribute(self::ATTR_FULL_NAME);
    }

    /**
     * @return null|string
     */
    public function getDateOfBirth()
    {
        return $this->getProfileAttribute(self::ATTR_DATE_OF_BIRTH);
    }

    /**
     * @return null|string
     */
    public function getGender()
    {
        return $this->getProfileAttribute(self::ATTR_GENDER);
    }

    /**
     * @return null|string
     */
    public function getNationality()
    {
        return $this->getProfileAttribute(self::ATTR_NATIONALITY);
    }

    /**
     * @return null|string
     */
    public function getPhoneNumber()
    {
        return $this->getProfileAttribute(self::ATTR_PHONE_NUMBER);
    }

    /**
     * @return null|string
     */
    public function getSelfie()
    {
        return $this->getProfileAttribute(self::ATTR_SELFIE);
    }

    /**
     * @return null|string
     */
    public function getEmailAddress()
    {
        return $this->getProfileAttribute(self::ATTR_EMAIL_ADDRESS);
    }

    /**
     * @return null|string
     */
    public function getPostalAddress()
    {
        return $this->getProfileAttribute(self::ATTR_POSTAL_ADDRESS);
    }
}