<?php

namespace Yoti\DocScan\Session\Retrieve\Instructions;

class ContactProfileResponse
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
     * @param string[] $contactProfileData
     */
    public function __construct(array $contactProfileData)
    {
        $this->firstName = $contactProfileData['first_name'] ?? null;
        $this->lastName = $contactProfileData['last_name'] ?? null;
        $this->email = $contactProfileData['email'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
}
