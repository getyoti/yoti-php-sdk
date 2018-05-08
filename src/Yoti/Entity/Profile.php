<?php
namespace Yoti\Entity;

class Profile
{
    private $familyName;

    private $givenNames;

    private $fullName;

    private $dateOfBirth;

    private $isAgeVerified;

    private $verifiedAge;

    private $gender;

    private $nationality;

    private $phoneNumber;

    private $selfie;

    private $emailAddress;

    private $postalAddress;

    private $structuredPostalAddress;

    private $emptyAttribute;

    /**
     * Profile constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        foreach($attributes as $name => $attribute)
        {
            $setter = 'set'.str_replace('_', '',ucwords($name, '_'));
            if(is_a($attribute, Attribute::class) && method_exists($this, $setter))
            {
                $this->{$setter}($attribute);
            }
        }

        $this->emptyAttribute = new Attribute('');
    }

    /**
     * @param Attribute $fullName
     */
    public function setFullName(Attribute $fullName)
    {
        if($fullName->getName() === Attribute::FULL_NAME)
        {
            $this->fullName = $fullName;
        }
    }

    /**
     * @return Attribute
     */
    public function getFullName()
    {
        return $this->getProfileAttribute($this->fullName);
    }

    /**
     * @param Attribute $familyName
     */
    public function setFamilyName(Attribute $familyName)
    {
        if($familyName->getName() === Attribute::FAMILY_NAME)
        {
            $this->familyName = $familyName;
        }
    }

    /**
     * @return Attribute
     */
    public function getFamilyName()
    {
        return $this->getProfileAttribute($this->familyName);
    }

    /**
     * @param Attribute $givenNames
     */
    public function setGivenNames(Attribute $givenNames)
    {
        if($givenNames->getName() === Attribute::GIVEN_NAMES)
        {
            $this->givenNames = $givenNames;
        }
    }

    /**
     * @return Attribute
     */
    public function getGivenNames()
    {
        return $this->getProfileAttribute($this->givenNames);
    }

    /**
     * @param Attribute $dateOfBirth
     */
    public function setDateOfBirth(Attribute $dateOfBirth)
    {
        if($dateOfBirth->getName() === Attribute::DATE_OF_BIRTH)
        {
            $this->dateOfBirth = $dateOfBirth;
        }
    }

    /**
     * @return Attribute
     */
    public function getDateOfBirth()
    {
        return $this->getProfileAttribute($this->dateOfBirth);
    }

    /**
     * @param Attribute $gender
     */
    public function setGender(Attribute $gender)
    {
        if($gender->getName() === Attribute::GENDER)
        {
            $this->gender = $gender;
        }
    }

    /**
     * @return Attribute
     */
    public function getGender()
    {
        return $this->getProfileAttribute($this->gender);
    }

    /**
     * @param Attribute $nationality
     */
    public function setNationality(Attribute $nationality)
    {
        if($nationality->getName() === Attribute::NATIONALITY)
        {
            $this->nationality = $nationality;
        }
    }

    /**
     * @return Attribute
     */
    public function getNationality()
    {
        return $this->getProfileAttribute($this->nationality);
    }

    /**
     * @param Attribute $phoneNumber
     */
    public function setPhoneNumber(Attribute $phoneNumber)
    {
        if($phoneNumber->getName() === Attribute::PHONE_NUMBER)
        {
            $this->phoneNumber = $phoneNumber;
        }
    }

    /**
     * @return Attribute
     */
    public function getPhoneNumber()
    {
        return $this->getProfileAttribute($this->phoneNumber);
    }

    /**
     * @param Attribute $selfie
     */
    public function setSelfie(Attribute $selfie)
    {
        if($selfie->getName() === Attribute::SELFIE)
        {
            $this->selfie = $selfie;
        }
    }

    /**
     * @return Attribute
     */
    public function getSelfie()
    {
        return $this->getProfileAttribute($this->selfie);
    }

    /**
     * @param Attribute $emailAddress
     */
    public function setEmailAddress(Attribute $emailAddress)
    {
        if($emailAddress->getName() === Attribute::EMAIL_ADDRESS)
        {
            $this->emailAddress = $emailAddress;
        }
    }

    /**
     * @return Attribute
     */
    public function getEmailAddress()
    {
        return $this->getProfileAttribute($this->emailAddress);
    }

    /**
     * @param Attribute $postalAddress
     */
    public function setPostalAddress(Attribute $postalAddress)
    {
        if($postalAddress->getName() === Attribute::POSTAL_ADDRESS)
        {
            $this->postalAddress = $postalAddress;
        }
    }

    /**
     * @return Attribute
     */
    public function getPostalAddress()
    {
        return $this->getProfileAttribute($this->postalAddress);
    }

    /**
     * @param Attribute $structuredPostalAddress
     */
    public function setStructuredPostalAddress(Attribute $structuredPostalAddress)
    {
        if ($structuredPostalAddress->getName() === Attribute::STRUCTURED_POSTAL_ADDRESS)
        {
            $this->structuredPostalAddress = $structuredPostalAddress;
        }
    }

    /**
     * @return null|array
     */
    public function getStructuredPostalAddress()
    {
        return $this->structuredPostalAddress;
    }

    /**
     * @param Attribute $isAgeVerified
     */
    public function setIsAgeVerified(Attribute $isAgeVerified)
    {
        if ($isAgeVerified->getName() === Attribute::IS_AGE_VERIFIED)
        {
            $this->isAgeVerified = $isAgeVerified;
        }
    }

    /**
     * @return Attribute
     */
    public function getIsAgeVerified()
    {
        return $this->getProfileAttribute($this->isAgeVerified);
    }

    /**
     * @param Attribute $verifiedAge
     */
    public function setVerifiedAge(Attribute $verifiedAge)
    {
        if ($verifiedAge->getName() === Attribute::VERIFIED_AGE)
        {
            $this->verifiedAge = $verifiedAge;
        }
    }

    /**
     * @return Attribute
     */
    public function getVerifiedAge()
    {
        return $this->getProfileAttribute($this->verifiedAge);
    }

    /**
     * @param $attributeName
     *
     * @return Attribute
     */
    private function getProfileAttribute($attributeName)
    {
        return NULL !== $attributeName ? $attributeName : $this->emptyAttribute;
    }
}