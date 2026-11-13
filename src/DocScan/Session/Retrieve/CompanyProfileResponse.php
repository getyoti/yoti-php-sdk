<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

class CompanyProfileResponse
{
    /**
     * @var string|null
     */
    private $companyName;

    /**
     * @param array<string, mixed> $companyProfileData
     */
    public function __construct(array $companyProfileData)
    {
        $this->companyName = $companyProfileData['company_name'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }
}
