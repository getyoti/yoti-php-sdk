<?php

declare(strict_types=1);

namespace Yoti\Test\IDV\Support;

use Yoti\IDV\Support\SupportedCountry;
use Yoti\IDV\Support\SupportedDocument;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Support\SupportedCountry
 */
class SupportedCountryTest extends TestCase
{
    private const SOME_COUNTRY_CODE = 'some-country-code';

    /**
     * @var SupportedCountry
     */
    private $supportedCountry;

    public function setup(): void
    {
        $this->supportedCountry = new SupportedCountry([
            'code' => self::SOME_COUNTRY_CODE,
            'supported_documents' => [
                [],
                [],
            ],
        ]);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getCode
     */
    public function shouldHaveCode()
    {
        $this->assertEquals(self::SOME_COUNTRY_CODE, $this->supportedCountry->getCode());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getSupportedDocuments
     */
    public function shouldHaveListOfSupportedDocuments()
    {
        $this->assertEquals(self::SOME_COUNTRY_CODE, $this->supportedCountry->getCode());
        $this->assertCount(2, $this->supportedCountry->getSupportedDocuments());
        $this->assertContainsOnlyInstancesOf(
            SupportedDocument::class,
            $this->supportedCountry->getSupportedDocuments()
        );
    }
}
