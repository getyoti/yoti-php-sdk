<?php

namespace Yoti\DocScan\Session\Instructions;

class ContactProfile
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
     * @param string|null $firstName
     * @param string|null $lastName
     * @param string|null $email
     */
    public function __construct(?string $firstName, ?string $lastName, ?string $email)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }

    /**
     * The first name set as part of the contact profile
     *
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * The last name set as part of the contact profile
     *
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * The email address set as part of the contact profile
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
}
