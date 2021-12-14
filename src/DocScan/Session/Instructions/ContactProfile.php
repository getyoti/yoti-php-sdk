<?php

namespace Yoti\DocScan\Session\Instructions;

class ContactProfile
{
    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $email;

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     */
    public function __construct(string $firstName, string $lastName, string $email)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }

    /**
     * The first name set as part of the contact profile
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * The last name set as part of the contact profile
     *
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * The email address set as part of the contact profile
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
