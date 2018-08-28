<?php
namespace Yoti\Entity;

class Profile
{
    private $profileData;

    /**
     * Profile constructor.
     *
     * @param array $profileData
     */
    public function __construct(array $profileData)
    {
        $this->profileData = $profileData;
    }

    /**
     * @return null|Attribute
     */
    public function getFullName()
    {
        return $this->getProfileAttribute(Attribute::FULL_NAME);
    }

    /**
     * @return null|Attribute
     */
    public function getFamilyName()
    {
        return $this->getProfileAttribute(Attribute::FAMILY_NAME);
    }

    /**
     * @return null|Attribute
     */
    public function getGivenNames()
    {
        return $this->getProfileAttribute(Attribute::GIVEN_NAMES);
    }

    /**
     * @return null|Attribute
     */
    public function getDateOfBirth()
    {
        return $this->getProfileAttribute(Attribute::DATE_OF_BIRTH);
    }

    /**
     * @return null|Attribute
     */
    public function getGender()
    {
        return $this->getProfileAttribute(Attribute::GENDER);
    }

    /**
     * @return null|Attribute
     */
    public function getNationality()
    {
        return $this->getProfileAttribute(Attribute::NATIONALITY);
    }

    /**
     * @return null|Attribute
     */
    public function getPhoneNumber()
    {
        return $this->getProfileAttribute(Attribute::PHONE_NUMBER);
    }

    /**
     * @return null|Attribute
     */
    public function getSelfie()
    {
        return $this->getProfileAttribute(Attribute::SELFIE);
    }

    /**
     * @return null|Attribute
     */
    public function getEmailAddress()
    {
        return $this->getProfileAttribute(Attribute::EMAIL_ADDRESS);
    }

    /**
     * @return null|Attribute
     */
    public function getPostalAddress()
    {
        return $this->getProfileAttribute(Attribute::POSTAL_ADDRESS);
    }

    /**
     * @return null|Attribute
     */
    public function getStructuredPostalAddress()
    {
        return $this->getProfileAttribute(Attribute::STRUCTURED_POSTAL_ADDRESS);
    }

    /**
     * @return null|Attribute
     */
    public function getAgeCondition()
    {
        return $this->getProfileAttribute(Attribute::AGE_CONDITION);
    }

    /**
     * @return null|Attribute
     */
    public function getVerifiedAge()
    {
        return $this->getProfileAttribute(Attribute::VERIFIED_AGE);
    }

    /**
     * @param $attributeName.
     *
     * @return null|Attribute
     */
    public function getProfileAttribute($attributeName)
    {
        if (isset($this->profileData[$attributeName])) {
            $attributeObj = $this->profileData[$attributeName];
            return $attributeObj instanceof Attribute ? $attributeObj : NULL;
        }
        return NULL;
    }
}