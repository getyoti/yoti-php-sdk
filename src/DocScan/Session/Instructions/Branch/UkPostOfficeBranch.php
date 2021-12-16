<?php

namespace Yoti\DocScan\Session\Instructions\Branch;

use Yoti\DocScan\Constants;

class UkPostOfficeBranch extends Branch
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $address;

    /**
     * @var string|null
     */
    private $postCode;

    /**
     * @var string|null
     */
    private $fadCode;

    /**
     * @var Location|null
     */
    private $location;

    public function __construct(
        ?string $name,
        ?string $address,
        ?string $postCode,
        ?string $fadCode = null,
        ?Location $location = null
    ) {
        parent::__construct(Constants::UK_POST_OFFICE);
        $this->name = $name;
        $this->address = $address;
        $this->postCode = $postCode;
        $this->fadCode = $fadCode;
        $this->location = $location;
    }

    /**
     * Returns the FAD code that has been set for the {@link UkPostOfficeBranch}
     *
     * @return string|null
     */
    public function getFadCode(): ?string
    {
        return $this->fadCode;
    }

    /**
     * Returns the name that has been set for the {@link UkPostOfficeBranch}
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Returns the address that has been set for the {@link UkPostOfficeBranch}
     *
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * Returns the post code that has been set for the {@link UkPostOfficeBranch}
     *
     * @return string|null
     */
    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    /**
     * Returns the {@link Location} that has been set for the {@link UkPostOfficeBranch}
     *
     * @return Location|null
     */
    public function getLocation(): ?Location
    {
        return $this->location;
    }
}
