<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create;

class ApplicantProfileBuilder
{
    /**
     * @var string|null
     */
    private $fullName;

    /**
     * @var string|null
     */
    private $dateOfBirth;

    /**
     * @var string|null
     */
    private $namePrefix;

    /**
     * @var StructuredPostalAddress|null
     */
    private $structuredPostalAddress;

    /**
     * @param string $fullName
     * @return $this
     */
    public function withFullName(string $fullName): self
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @param string $dateOfBirth
     * @return $this
     */
    public function withDateOfBirth(string $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }

    /**
     * @param string $namePrefix
     * @return $this
     */
    public function withNamePrefix(string $namePrefix): self
    {
        $this->namePrefix = $namePrefix;
        return $this;
    }

    /**
     * @param StructuredPostalAddress $structuredPostalAddress
     * @return $this
     */
    public function withStructuredPostalAddress(StructuredPostalAddress $structuredPostalAddress): self
    {
        $this->structuredPostalAddress = $structuredPostalAddress;
        return $this;
    }

    /**
     * @return ApplicantProfile
     */
    public function build(): ApplicantProfile
    {
        return new ApplicantProfile(
            $this->fullName,
            $this->dateOfBirth,
            $this->namePrefix,
            $this->structuredPostalAddress
        );
    }
}
