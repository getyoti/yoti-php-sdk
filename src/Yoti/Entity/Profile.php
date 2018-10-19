<?php
namespace Yoti\Entity;

class Profile extends BaseProfile
{
    const ATTR_FAMILY_NAME = 'family_name';
    const ATTR_GIVEN_NAMES = 'given_names';
    const ATTR_FULL_NAME = 'full_name';
    const ATTR_DATE_OF_BIRTH = 'date_of_birth';
    const ATTR_AGE_CONDITION = 'age_condition';
    const ATTR_VERIFIED_AGE = 'verified_age';
    const ATTR_GENDER = 'gender';
    const ATTR_NATIONALITY = 'nationality';
    const ATTR_PHONE_NUMBER = 'phone_number';
    const ATTR_SELFIE = 'selfie';
    const ATTR_EMAIL_ADDRESS = 'email_address';
    const ATTR_POSTAL_ADDRESS = 'postal_address';
    const ATTR_DOCUMENT_DETAILS = "document_details";
    const ATTR_STRUCTURED_POSTAL_ADDRESS = 'structured_postal_address';

    /**
     * @return null|Attribute
     */
    public function getFullName()
    {
        return $this->getProfileAttribute(self::ATTR_FULL_NAME);
    }

    /**
     * @return null|Attribute
     */
    public function getFamilyName()
    {
        return $this->getProfileAttribute(self::ATTR_FAMILY_NAME);
    }

    /**
     * @return null|Attribute
     */
    public function getGivenNames()
    {
        return $this->getProfileAttribute(self::ATTR_GIVEN_NAMES);
    }

    /**
     * @return null|Attribute
     */
    public function getDateOfBirth()
    {
        return $this->getProfileAttribute(self::ATTR_DATE_OF_BIRTH);
    }

    /**
     * @return null|Attribute
     */
    public function getGender()
    {
        return $this->getProfileAttribute(self::ATTR_GENDER);
    }

    /**
     * @return null|Attribute
     */
    public function getNationality()
    {
        return $this->getProfileAttribute(self::ATTR_NATIONALITY);
    }

    /**
     * @return null|Attribute
     */
    public function getPhoneNumber()
    {
        return $this->getProfileAttribute(self::ATTR_PHONE_NUMBER);
    }

    /**
     * @return null|Attribute
     */
    public function getSelfie()
    {
        return $this->getProfileAttribute(self::ATTR_SELFIE);
    }

    /**
     * @return null|Attribute
     */
    public function getEmailAddress()
    {
        return $this->getProfileAttribute(self::ATTR_EMAIL_ADDRESS);
    }

    /**
     * @return null|Attribute
     */
    public function getPostalAddress()
    {
        return $this->getProfileAttribute(self::ATTR_POSTAL_ADDRESS);
    }

    /**
     * @return null|Attribute
     */
    public function getStructuredPostalAddress()
    {
        return $this->getProfileAttribute(self::ATTR_STRUCTURED_POSTAL_ADDRESS);
    }

    /**
     * @return null|Attribute
     */
    public function getAgeCondition()
    {
        return $this->getProfileAttribute(self::ATTR_AGE_CONDITION);
    }

    /**
     * @return null|Attribute
     */
    public function getVerifiedAge()
    {
        return $this->getProfileAttribute(self::ATTR_VERIFIED_AGE);
    }

    public function getDocumentDetails()
    {
        return $this->getProfileAttribute(self::ATTR_DOCUMENT_DETAILS);
    }

    /**
     * Return all the derived attributes from the DOB e.g 'Age Over', 'Age Under'
     *
     * @return array
     */
    public function getAgeVerifications()
    {
        return $this->profileData['age_verifications'];
    }
}