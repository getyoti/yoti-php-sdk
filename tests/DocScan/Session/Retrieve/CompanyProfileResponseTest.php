<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\CompanyProfileResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\CompanyProfileResponse
 */
class CompanyProfileResponseTest extends TestCase
{
    private const SOME_COMPANY_NAME = 'Acme Corp';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getCompanyName
     */
    public function shouldReturnCompanyName()
    {
        $response = new CompanyProfileResponse(['company_name' => self::SOME_COMPANY_NAME]);

        $this->assertEquals(self::SOME_COMPANY_NAME, $response->getCompanyName());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getCompanyName
     */
    public function shouldReturnNullWhenCompanyNameNotPresent()
    {
        $response = new CompanyProfileResponse([]);

        $this->assertNull($response->getCompanyName());
    }
}
