<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Create;

use Yoti\DocScan\Session\Create\CompanyProfile;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\CompanyProfile
 */
class CompanyProfileTest extends TestCase
{
    private const SOME_COMPANY_NAME = 'Acme Corp';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getCompanyName
     */
    public function shouldReturnCompanyName()
    {
        $companyProfile = new CompanyProfile(self::SOME_COMPANY_NAME);

        $this->assertEquals(self::SOME_COMPANY_NAME, $companyProfile->getCompanyName());
    }

    /**
     * @test
     * @covers ::jsonSerialize
     * @covers ::__construct
     * @covers ::getCompanyName
     */
    public function shouldSerializeToJsonWithCompanyName()
    {
        $companyProfile = new CompanyProfile(self::SOME_COMPANY_NAME);

        $this->assertJsonStringEqualsJsonString(
            json_encode(['company_name' => self::SOME_COMPANY_NAME]),
            json_encode($companyProfile)
        );
    }

    /**
     * @test
     * @covers ::jsonSerialize
     * @covers ::__construct
     */
    public function shouldOmitNullCompanyNameFromJson()
    {
        $companyProfile = new CompanyProfile(null);

        $this->assertJsonStringEqualsJsonString(
            json_encode((object)[]),
            json_encode($companyProfile)
        );
    }
}
