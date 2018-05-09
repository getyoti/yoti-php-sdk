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
    private $name;

    /**
     * @var string
     */
    private $value;

    /**
     * @var array
     */
    private $sources = [];

    /**
     * @var array
     */
    private $verifiers = [];

    /**
     * Attribute constructor.
     *
     * @param string $name
     * @param null|string $value
     * @param array $sources
     * @param array $verifiers
     */
    public function __construct($name, $value = NULL, $sources = [], $verifiers = [])
    {
        $this->setName($name);
        $this->setValue($value);
        $this->setSources($sources);
        $this->setVerifiers($verifiers);
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        if(!empty($name))
        {
            $this->name = $name;
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        if(!empty($value))
        {
            $this->value = $value;
        }
    }

    /**
     * @return null|string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param array $sources
     */
    public function setSources(array $sources)
    {
        if(!empty($sources))
        {
            $this->sources = $sources;
        }
    }

    /**
     * @return array
     */
    public function getSources()
    {
        return $this->sources;
    }

    /**
     * @param array $verifiers
     */
    public function setVerifiers(array $verifiers)
    {
        if(!empty($verifiers))
        {
            $this->verifiers = $verifiers;
        }
    }

    /**
     * @return array
     */
    public function getVerifiers()
    {
        return $this->verifiers;
    }
}