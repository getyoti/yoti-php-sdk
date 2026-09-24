<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\DigitalIdShareErrorResponse;
use Yoti\DocScan\Session\Retrieve\DigitalIdShareResponse;
use Yoti\Test\TestCase;
use Yoti\Util\DateTime;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\DigitalIdShareResponse
 */
class DigitalIdShareResponseTest extends TestCase
{
    private const SOME_ID = 'someId';
    private const SOME_DOCUMENT_TYPE = 'DIGITAL_AADHAAR';
    private const SOME_ISSUING_COUNTRY = 'IND';
    private const SOME_PROVIDER = 'DIGILOCKER';
    private const SOME_CREATED_AT = '2021-06-11T11:39:24Z';
    private const SOME_LAST_UPDATED = '2021-06-12T11:39:24Z';
    private const SOME_RESOURCE_ID = 'someResourceId';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getId
     * @covers ::getDocumentType
     * @covers ::getIssuingCountry
     * @covers ::getProvider
     * @covers ::getCreatedAt
     * @covers ::getLastUpdated
     * @covers ::getResourceId
     * @covers ::getError
     */
    public function shouldBuildCorrectly()
    {
        $result = new DigitalIdShareResponse([
            'id' => self::SOME_ID,
            'document_type' => self::SOME_DOCUMENT_TYPE,
            'issuing_country' => self::SOME_ISSUING_COUNTRY,
            'provider' => self::SOME_PROVIDER,
            'created_at' => self::SOME_CREATED_AT,
            'last_updated' => self::SOME_LAST_UPDATED,
            'resource_id' => self::SOME_RESOURCE_ID,
            'error' => [
                'code' => 'someCode',
                'description' => 'someDescription',
            ],
        ]);

        $this->assertEquals(self::SOME_ID, $result->getId());
        $this->assertEquals(self::SOME_DOCUMENT_TYPE, $result->getDocumentType());
        $this->assertEquals(self::SOME_ISSUING_COUNTRY, $result->getIssuingCountry());
        $this->assertEquals(self::SOME_PROVIDER, $result->getProvider());
        $this->assertEquals(DateTime::stringToDateTime(self::SOME_CREATED_AT), $result->getCreatedAt());
        $this->assertEquals(DateTime::stringToDateTime(self::SOME_LAST_UPDATED), $result->getLastUpdated());
        $this->assertEquals(self::SOME_RESOURCE_ID, $result->getResourceId());
        $this->assertInstanceOf(DigitalIdShareErrorResponse::class, $result->getError());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldNotThrowExceptionWhenMissingValues()
    {
        $result = new DigitalIdShareResponse([]);

        $this->assertNull($result->getId());
        $this->assertNull($result->getDocumentType());
        $this->assertNull($result->getIssuingCountry());
        $this->assertNull($result->getProvider());
        $this->assertNull($result->getCreatedAt());
        $this->assertNull($result->getLastUpdated());
        $this->assertNull($result->getResourceId());
        $this->assertNull($result->getError());
    }
}
