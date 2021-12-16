<?php

namespace Yoti\DocScan\Session\Instructions\Branch;

class UkPostOfficeBranchBuilder
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

    /**
     * @param string $name
     * @return $this
     */
    public function withName(string $name): UkPostOfficeBranchBuilder
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $address
     * @return $this
     */
    public function withAddress(string $address): UkPostOfficeBranchBuilder
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @param string $postCode
     * @return $this
     */
    public function withPostCode(string $postCode): UkPostOfficeBranchBuilder
    {
        $this->postCode = $postCode;
        return $this;
    }

    /**
     * @param string $fadCode
     * @return $this
     */
    public function withFadCode(string $fadCode): UkPostOfficeBranchBuilder
    {
        $this->fadCode = $fadCode;
        return $this;
    }

    /**
     * @param Location $location
     * @return $this
     */
    public function withLocation(Location $location): UkPostOfficeBranchBuilder
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return UkPostOfficeBranch
     */
    public function build(): UkPostOfficeBranch
    {
        return new UkPostOfficeBranch($this->name, $this->address, $this->postCode, $this->fadCode, $this->location);
    }
}
