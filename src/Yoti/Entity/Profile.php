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

    public function setFullName(Attribute $fullName)
    {
        if($fullName->getName() === Attribute::FULL_NAME)
        {
            $this->fullName = $fullName;
        }
    }

    public function getFullName()
    {
        return $this->getProfileAttribute($this->fullName);
    }

    public function setFamilyName(Attribute $familyName)
    {
        if($familyName->getName() === Attribute::FAMILY_NAME)
        {
            $this->familyName = $familyName;
        }
    }

    public function getFamilyName()
    {
        return $this->getProfileAttribute($this->familyName);
    }

    public function setGivenNames(Attribute $givenNames)
    {
        if($givenNames->getName() === Attribute::GIVEN_NAMES)
        {
            $this->givenNames = $givenNames;
        }
    }

    public function getGivenNames()
    {
        return $this->getProfileAttribute($this->givenNames);
    }

    public function setDateOfBirth(Attribute $dateOfBirth)
    {
        if($dateOfBirth->getName() === Attribute::DATE_OF_BIRTH)
        {
            $this->dateOfBirth = $dateOfBirth;
        }
    }

    public function getDateOfBirth()
    {
        return $this->getProfileAttribute($this->dateOfBirth);
    }

    public function setGender(Attribute $gender)
    {
        if($gender->getName() === Attribute::GENDER)
        {
            $this->gender = $gender;
        }
    }

    public function getGender()
    {
        return $this->getProfileAttribute($this->gender);
    }

    public function setNationality(Attribute $nationality)
    {
        if($nationality->getName() === Attribute::NATIONALITY)
        {
            $this->nationality = $nationality;
        }
    }

    public function getNationality()
    {
        return $this->getProfileAttribute($this->nationality);
    }

    public function setPhoneNumber(Attribute $phoneNumber)
    {
        if($phoneNumber->getName() === Attribute::PHONE_NUMBER)
        {
            $this->phoneNumber = $phoneNumber;
        }
    }

    public function getPhoneNumber()
    {
        return $this->getProfileAttribute($this->phoneNumber);
    }

    public function setSelfie(Attribute $selfie)
    {
        if($selfie->getName() === Attribute::SELFIE)
        {
            $this->selfie = $selfie;
        }
    }

    public function getSelfie()
    {
        return $this->getProfileAttribute($this->selfie);
    }

    public function setEmailAddress(Attribute $emailAddress)
    {
        if($emailAddress->getName() === Attribute::EMAIL_ADDRESS)
        {
            $this->emailAddress = $emailAddress;
        }
    }

    public function getEmailAddress()
    {
        return $this->getProfileAttribute($this->emailAddress);
    }

    public function setPostalAddress(Attribute $postalAddress)
    {
        if($postalAddress->getName() === Attribute::POSTAL_ADDRESS)
        {
            $this->postalAddress = $postalAddress;
        }
    }

    public function getPostalAddress()
    {
        return $this->getProfileAttribute($this->postalAddress);
    }

    public function setStructuredPostalAddress(Attribute $structuredPostalAddress)
    {
        if ($structuredPostalAddress->getName() === Attribute::STRUCTURED_POSTAL_ADDRESS)
        {
            $this->structuredPostalAddress = $structuredPostalAddress;
        }
    }

    public function getStructuredPostalAddress()
    {
        return $this->structuredPostalAddress;
    }

    public function setIsAgeVerified(Attribute $isAgeVerified)
    {
        if ($isAgeVerified->getName() === Attribute::IS_AGE_VERIFIED)
        {
            $this->isAgeVerified = $isAgeVerified;
        }
    }

    public function getIsAgeVerified()
    {
        return $this->getProfileAttribute($this->isAgeVerified);
    }

    public function setVerifiedAge(Attribute $verifiedAge)
    {
        if ($verifiedAge->getName() === Attribute::VERIFIED_AGE)
        {
            $this->verifiedAge = $verifiedAge;
        }
    }

    public function getVerifiedAge()
    {
        return $this->getProfileAttribute($this->verifiedAge);
    }

    private function getProfileAttribute($attributeName)
    {
        return NULL !== $attributeName ? $attributeName : $this->emptyAttribute;
    }
}