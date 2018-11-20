<?php
namespace Yoti\Entity;

class Profile extends BaseProfile
{
    const AGE_OVER_FORMAT = 'age_over:%d';
    const AGE_UNDER_FORMAT = 'age_under:%d';

    const ATTR_FAMILY_NAME = 'family_name';
    const ATTR_GIVEN_NAMES = 'given_names';
    const ATTR_FULL_NAME = 'full_name';
    const ATTR_DATE_OF_BIRTH = 'date_of_birth';
    const ATTR_AGE_VERIFICATIONS = 'age_verifications';
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
     * Return postal_address or structured_postal_address.formatted_address.
     *
     * @return null|Attribute
     */
    public function getPostalAddress()
    {
        $postalAddress = $this->getProfileAttribute(self::ATTR_POSTAL_ADDRESS);
        if (NULL === $postalAddress) {
            // Get it from structured_postal_address.formatted_address
            $postalAddress = $this->getFormattedAddress();
        }
        return $postalAddress;
    }

    /**
     * @return null|Attribute
     */
    public function getStructuredPostalAddress()
    {
        return $this->getProfileAttribute(self::ATTR_STRUCTURED_POSTAL_ADDRESS);
    }

    public function getDocumentDetails()
    {
        return $this->getProfileAttribute(self::ATTR_DOCUMENT_DETAILS);
    }

    /**
     * Return all derived attributes from the DOB e.g 'Age Over', 'Age Under'
     * As a list of AgeVerification
     *
     * @return array
     * e.g [
     *      'age_under:18' => new AgeVerification(...),
     *      'age_over:50' => new AgeVerification(...),
     *      ...
     * ]
     */
    public function getAgeVerifications()
    {
        return isset($this->profileData[self::ATTR_AGE_VERIFICATIONS])
            ? $this->profileData[self::ATTR_AGE_VERIFICATIONS] : [];
    }

    /**
     * Return AgeVerification for age_over:xx.
     *
     * @param int $age
     *
     * @return null|AgeVerification
     */
    public function findAgeOverVerification($age)
    {
        $ageOverAttr = sprintf(self::AGE_OVER_FORMAT, (int) $age);
        return $this->getAgeVerificationByAttribute($ageOverAttr);
    }

    /**
     * Return AgeVerification for age_under:xx.
     *
     * @param int $age
     *
     * @return null|AgeVerification
     */
    public function findAgeUnderVerification($age)
    {
        $ageUnderAttr = sprintf(self::AGE_UNDER_FORMAT, (int) $age);
        return $this->getAgeVerificationByAttribute($ageUnderAttr);
    }

    /**
     * Return AgeVerification.
     *
     * @param string $ageAttr
     *
     * @return mixed|null
     */
    private function getAgeVerificationByAttribute($ageAttr)
    {
        $ageVerifications = $this->getAgeVerifications();
        return isset($ageVerifications[$ageAttr]) ? $ageVerifications[$ageAttr] : NULL;
    }

    /**
     * @return null|Attribute
     */
    private function getFormattedAddress()
    {
        $postalAddress = NULL;
        // Get it from structured_postal_address.formatted_address
        $structuredPostalAddress = $this->getStructuredPostalAddress();
        if (NULL !== $structuredPostalAddress)
        {
            $valueArr = $structuredPostalAddress->getValue();
            if (
                is_array($valueArr)
                && isset($valueArr['formatted_address'])
            ) {
                $postalAddressValue = $valueArr['formatted_address'];

                $postalAddress = new Attribute(
                    self::ATTR_POSTAL_ADDRESS,
                    $postalAddressValue,
                    $structuredPostalAddress->getSources(),
                    $structuredPostalAddress->getVerifiers()
                );
            }
        }
        return $postalAddress;
    }
}