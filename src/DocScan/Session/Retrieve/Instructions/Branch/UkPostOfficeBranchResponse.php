<?php

namespace Yoti\DocScan\Session\Retrieve\Instructions\Branch;

class UkPostOfficeBranchResponse extends BranchResponse
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
     * @var LocationResponse|null
     */
    private $location;

    /**
     * @param array<string, mixed> $branchData
     */
    public function __construct(array $branchData)
    {
        $this->type = $branchData['type'];
        $this->fadCode = $branchData['fad_code'];
        $this->name = $branchData['name'];
        $this->address = $branchData['address'];
        $this->postCode = $branchData['post_code'];
        $this->location = new LocationResponse($branchData['location']);
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @return string|null
     */
    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    /**
     * @return string|null
     */
    public function getFadCode(): ?string
    {
        return $this->fadCode;
    }

    /**
     * @return LocationResponse
     */
    public function getLocation(): ?LocationResponse
    {
        return $this->location;
    }
}
