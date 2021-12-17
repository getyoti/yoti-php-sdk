<?php

namespace Yoti\DocScan\Session\Instructions;

class ContactProfileBuilder
{
    /**
     * @var string|null
     */
    private $firstName;

    /**
     * @var string|null
     */
    private $lastName;

    /**
     * @var string|null
     */
    private $email;

    /**
     * Sets the first name that will be used as part of the contact profile
     *
     * @param string $firstName
     * @return $this
     */
    public function withFirstName(string $firstName): ContactProfileBuilder
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Sets the last name that will be used as part of the contact profile
     *
     * @param string $lastName
     * @return $this
     */
    public function withLastName(string $lastName): ContactProfileBuilder
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Sets the email address that will be used as part of the contact profile
     *
     * @param string $email
     * @return $this
     */
    public function withEmail(string $email): ContactProfileBuilder
    {
        $this->email = $email;
        return $this;
    }

    public function build(): ContactProfile
    {
        return new ContactProfile($this->firstName, $this->lastName, $this->email);
    }
}
