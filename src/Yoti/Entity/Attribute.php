<?php
namespace Yoti\Entity;

class Attribute
{
    const FAMILY_NAME = 'family_name';
    const GIVEN_NAMES = 'given_names';
    const FULL_NAME = 'full_name';
    const DATE_OF_BIRTH = 'date_of_birth';
    const AGE_CONDITION = 'age_condition';
    const VERIFIED_AGE = 'verified_age';
    const GENDER = 'gender';
    const NATIONALITY = 'nationality';
    const PHONE_NUMBER = 'phone_number';
    const SELFIE = 'selfie';
    const EMAIL_ADDRESS = 'email_address';
    const POSTAL_ADDRESS = 'postal_address';
    const STRUCTURED_POSTAL_ADDRESS = 'structured_postal_address';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var array
     */
    protected $sources;

    /**
     * @var array
     */
    protected $verifiers;

    /**
     * Attribute constructor.
     *
     * @param string $name
     * @param null|string $value
     * @param array $sources
     * @param array $verifiers
     */
    public function __construct($name, $value = NULL, array $sources, array $verifiers)
    {
        $this->name = $name;
        $this->value = $value;
        $this->sources = $sources;
        $this->verifiers = $verifiers;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function getSources()
    {
        return $this->sources;
    }

    /**
     * @return array
     */
    public function getVerifiers()
    {
        return $this->verifiers;
    }
}