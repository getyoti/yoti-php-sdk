<?php

declare(strict_types=1);

namespace Yoti\Profile;

use Yoti\Profile\Attribute\AgeVerification;

/**
 * Profile of a human user with convenience methods to access well-known attributes.
 */
class UserProfile extends BaseProfile
{
    public const AGE_OVER = 'age_over:';
    public const AGE_UNDER = 'age_under:';

    public const ATTR_FAMILY_NAME = 'family_name';
    public const ATTR_GIVEN_NAMES = 'given_names';
    public const ATTR_FULL_NAME = 'full_name';
    public const ATTR_DATE_OF_BIRTH = 'date_of_birth';
    public const ATTR_GENDER = 'gender';
    public const ATTR_NATIONALITY = 'nationality';
    public const ATTR_PHONE_NUMBER = 'phone_number';
    public const ATTR_SELFIE = 'selfie';
    public const ATTR_EMAIL_ADDRESS = 'email_address';
    public const ATTR_POSTAL_ADDRESS = 'postal_address';
    public const ATTR_DOCUMENT_DETAILS = "document_details";
    public const ATTR_DOCUMENT_IMAGES = 'document_images';
    public const ATTR_STRUCTURED_POSTAL_ADDRESS = 'structured_postal_address';

    /** @var \Yoti\Profile\Attribute\AgeVerification[] */
    private $ageVerifications;

    /**
     * The full name attribute.
     *
     * @return Attribute|null
     */
    public function getFullName(): ?Attribute
    {
        return $this->getProfileAttribute(self::ATTR_FULL_NAME);
    }

    /**
     * Corresponds to primary name in passport, and surname in English.
     *
     * @return Attribute|null
     */
    public function getFamilyName(): ?Attribute
    {
        return $this->getProfileAttribute(self::ATTR_FAMILY_NAME);
    }

    /**
     * Corresponds to secondary names in passport, and first/middle names in English.
     *
     * @return Attribute|null
     */
    public function getGivenNames(): ?Attribute
    {
        return $this->getProfileAttribute(self::ATTR_GIVEN_NAMES);
    }

    /**
     * Date of birth.
     *
     * @return Attribute|null
     */
    public function getDateOfBirth(): ?Attribute
    {
        return $this->getProfileAttribute(self::ATTR_DATE_OF_BIRTH);
    }

    /**
     * Corresponds to the gender in the passport; will be one of the strings
     * "MALE", "FEMALE", "TRANSGENDER" or "OTHER".
     *
     * @return Attribute|null
     */
    public function getGender(): ?Attribute
    {
        return $this->getProfileAttribute(self::ATTR_GENDER);
    }

    /**
     * Corresponds to the nationality in the passport.
     *
     * @return Attribute|null
     */
    public function getNationality(): ?Attribute
    {
        return $this->getProfileAttribute(self::ATTR_NATIONALITY);
    }

    /**
     * The user's phone number, as verified at registration time. This will be a number with + for
     * international prefix and no spaces, e.g. "+447777123456".
     *
     * @return Attribute|null
     */
    public function getPhoneNumber(): ?Attribute
    {
        return $this->getProfileAttribute(self::ATTR_PHONE_NUMBER);
    }

    /**
     * Photograph of user, encoded as a JPEG image.
     *
     * @return Attribute|null
     */
    public function getSelfie(): ?Attribute
    {
        return $this->getProfileAttribute(self::ATTR_SELFIE);
    }

    /**
     * The user's verified email address.
     *
     * @return Attribute|null
     */
    public function getEmailAddress(): ?Attribute
    {
        return $this->getProfileAttribute(self::ATTR_EMAIL_ADDRESS);
    }

    /**
     * The user's postal address as a string.
     *
     * @return Attribute|null
     */
    public function getPostalAddress(): ?Attribute
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
     * @return Attribute|null
     */
    public function getStructuredPostalAddress(): ?Attribute
    {
        return $this->getProfileAttribute(self::ATTR_STRUCTURED_POSTAL_ADDRESS);
    }

    /**
     * Document details.
     *
     * @return Attribute|null
     */
    public function getDocumentDetails(): ?Attribute
    {
        return $this->getProfileAttribute(self::ATTR_DOCUMENT_DETAILS);
    }

    /**
     * Return a list of document images.
     *
     * @return Attribute|null
     */
    public function getDocumentImages(): ?Attribute
    {
        return $this->getProfileAttribute(self::ATTR_DOCUMENT_IMAGES);
    }

    /**
     * Return all derived attributes from the DOB e.g 'Age Over', 'Age Under'
     * As a list of AgeVerification
     *
     * @return \Yoti\Profile\Attribute\AgeVerification[]
     * e.g [
     *      'age_under:18' => new AgeVerification(...),
     *      'age_over:50' => new AgeVerification(...),
     *      ...
     * ]
     */
    public function getAgeVerifications(): array
    {
        $this->findAllAgeVerifications();
        return $this->ageVerifications;
    }

    /**
     * Return AgeVerification for age_over:xx.
     *
     * @param int $age
     *
     * @return \Yoti\Profile\Attribute\AgeVerification|null
     */
    public function findAgeOverVerification(int $age): ?AgeVerification
    {
        return $this->getAgeVerification(self::AGE_OVER, $age);
    }

    /**
     * Return AgeVerification for age_under:xx.
     *
     * @param int $age
     *
     * @return \Yoti\Profile\Attribute\AgeVerification|null
     */
    public function findAgeUnderVerification(int $age): ?AgeVerification
    {
        return $this->getAgeVerification(self::AGE_UNDER, $age);
    }

    /**
     * Return AgeVerification.
     *
     * @param string $type
     * @param int $age
     *
     * @return \Yoti\Profile\Attribute\AgeVerification|null
     */
    private function getAgeVerification(string $type, int $age): ?AgeVerification
    {
        $attrName = $type . (string) $age;
        return $this->getAgeVerifications()[$attrName] ?? null;
    }

    /**
     * @param string $name
     *
     * @return \Yoti\Profile\Attribute[]
     */
    private function findAttributesStartingWith($name): array
    {
        return array_filter(
            $this->getAttributesList(),
            function (Attribute $attr) use ($name): bool {
                return strpos($attr->getName(), $name) === 0;
            }
        );
    }

    /**
     * Finds and sets all age verifications.
     */
    private function findAllAgeVerifications(): void
    {
        if (!isset($this->ageVerifications)) {
            $this->ageVerifications = [];
            foreach ([self::AGE_OVER, self::AGE_UNDER] as $format) {
                foreach ($this->findAttributesStartingWith($format) as $attr) {
                    $this->ageVerifications[$attr->getName()] = new AgeVerification($attr);
                }
            }
        }
    }

    /**
     * @return \Yoti\Profile\Attribute|null
     */
    private function getFormattedAddress(): ?Attribute
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
                    $structuredPostalAddress->getAnchors()
                );
            }
        }
        return $postalAddress;
    }
}
