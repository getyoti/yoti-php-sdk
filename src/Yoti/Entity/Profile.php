<?php

namespace Yoti\Entity;

/**
 * Profile of a human user with convenience methods to access well-known attributes.
 */
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
    const ATTR_DOCUMENT_IMAGES = 'document_images';
    const ATTR_STRUCTURED_POSTAL_ADDRESS = 'structured_postal_address';

    /**
     * The full name attribute.
     *
     * @return null|Attribute
     */
    public function getFullName()
    {
        return $this->getProfileAttribute(self::ATTR_FULL_NAME);
    }

    /**
     * Corresponds to primary name in passport, and surname in English.
     *
     * @return null|Attribute
     */
    public function getFamilyName()
    {
        return $this->getProfileAttribute(self::ATTR_FAMILY_NAME);
    }

    /**
     * Corresponds to secondary names in passport, and first/middle names in English.
     *
     * @return null|Attribute
     */
    public function getGivenNames()
    {
        return $this->getProfileAttribute(self::ATTR_GIVEN_NAMES);
    }

    /**
     * Date of birth.
     *
     * @return null|Attribute
     */
    public function getDateOfBirth()
    {
        return $this->getProfileAttribute(self::ATTR_DATE_OF_BIRTH);
    }

    /**
     * Corresponds to the gender in the passport; will be one of the strings
     * "MALE", "FEMALE", "TRANSGENDER" or "OTHER".
     *
     * @return null|Attribute
     */
    public function getGender()
    {
        return $this->getProfileAttribute(self::ATTR_GENDER);
    }

    /**
     * Corresponds to the nationality in the passport.
     *
     * @return null|Attribute
     */
    public function getNationality()
    {
        return $this->getProfileAttribute(self::ATTR_NATIONALITY);
    }

    /**
     * The user's phone number, as verified at registration time. This will be a number with + for
     * international prefix and no spaces, e.g. "+447777123456".
     *
     * @return null|Attribute
     */
    public function getPhoneNumber()
    {
        return $this->getProfileAttribute(self::ATTR_PHONE_NUMBER);
    }

    /**
     * Photograph of user, encoded as a JPEG image.
     *
     * @return null|Attribute
     */
    public function getSelfie()
    {
        return $this->getProfileAttribute(self::ATTR_SELFIE);
    }

    /**
     * The user's verified email address.
     *
     * @return null|Attribute
     */
    public function getEmailAddress()
    {
        return $this->getProfileAttribute(self::ATTR_EMAIL_ADDRESS);
    }

    /**
     * The user's postal address as a string.
     *
     * @return null|Attribute
     */
    public function getPostalAddress()
    {
        $postalAddress = $this->getProfileAttribute(self::ATTR_POSTAL_ADDRESS);
        if (null === $postalAddress) {
            // Get it from structured_postal_address.formatted_address
            $postalAddress = $this->getFormattedAddress();
        }
        return $postalAddress;
    }

    /**
     * The user's structured postal address as a JSON.
     *
     * @return null|Attribute
     */
    public function getStructuredPostalAddress()
    {
        return $this->getProfileAttribute(self::ATTR_STRUCTURED_POSTAL_ADDRESS);
    }

    /**
     * Document details.
     *
     * @return null|Attribute
     */
    public function getDocumentDetails()
    {
        return $this->getProfileAttribute(self::ATTR_DOCUMENT_DETAILS);
    }

    /**
     * Return a list of document images.
     *
     * @return array
     */
    public function getDocumentImages()
    {
        return $this->getProfileAttribute(self::ATTR_DOCUMENT_IMAGES);
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
        return isset($ageVerifications[$ageAttr]) ? $ageVerifications[$ageAttr] : null;
    }

    /**
     * @return null|Attribute
     */
    private function getFormattedAddress()
    {
        $postalAddress = null;
        // Get it from structured_postal_address.formatted_address
        $structuredPostalAddress = $this->getStructuredPostalAddress();
        if (null !== $structuredPostalAddress) {
            $valueArr = $structuredPostalAddress->getValue();
            if (
                is_array($valueArr)
                && isset($valueArr['formatted_address'])
            ) {
                $postalAddressValue = $valueArr['formatted_address'];

                $postalAddress = new Attribute(
                    self::ATTR_POSTAL_ADDRESS,
                    $postalAddressValue,
                    $this->getAttributeAnchorMap($structuredPostalAddress)
                );
            }
        }
        return $postalAddress;
    }

    /**
     * Get anchor map for provided anchor.
     *
     * @param Attribute $attribute
     * @return array attribute map
     */
    private function getAttributeAnchorMap(Attribute $attribute)
    {
        return [
            Anchor::TYPE_UNKNOWN_NAME => array_filter($attribute->getAnchors(), function ($anchor) {
                return $anchor->getType() == Anchor::TYPE_UNKNOWN_NAME;
            }),
            Anchor::TYPE_VERIFIER_OID => $attribute->getVerifiers(),
            Anchor::TYPE_SOURCE_OID => $attribute->getSources(),
        ];
    }
}
