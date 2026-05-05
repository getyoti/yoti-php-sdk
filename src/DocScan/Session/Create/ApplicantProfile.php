<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Create;

use JsonSerializable;
use stdClass;
use Yoti\Util\Json;

class ApplicantProfile implements JsonSerializable
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
     * @param string|null $fullName
     * @param string|null $dateOfBirth
     * @param string|null $namePrefix
     * @param StructuredPostalAddress|null $structuredPostalAddress
     */
    public function __construct(
        ?string $fullName,
        ?string $dateOfBirth,
        ?string $namePrefix,
        ?StructuredPostalAddress $structuredPostalAddress
    ) {
        $this->fullName = $fullName;
        $this->dateOfBirth = $dateOfBirth;
        $this->namePrefix = $namePrefix;
        $this->structuredPostalAddress = $structuredPostalAddress;
    }

    /**
     * @return stdClass
     */
    public function jsonSerialize(): stdClass
    {
        return (object) Json::withoutNullValues([
            'full_name' => $this->fullName,
            'date_of_birth' => $this->dateOfBirth,
            'name_prefix' => $this->namePrefix,
            'structured_postal_address' => $this->structuredPostalAddress,
        ]);
    }

    /**
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     * @return string|null
     */
    public function getDateOfBirth(): ?string
    {
        return $this->dateOfBirth;
    }

    /**
     * @return string|null
     */
    public function getNamePrefix(): ?string
    {
        return $this->namePrefix;
    }

    /**
     * @return StructuredPostalAddress|null
     */
    public function getStructuredPostalAddress(): ?StructuredPostalAddress
    {
        return $this->structuredPostalAddress;
    }
}
