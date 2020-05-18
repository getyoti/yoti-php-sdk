<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Support;

use Yoti\DocScan\Support\SupportedCountry;
use Yoti\DocScan\Support\SupportedDocumentsResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Support\SupportedDocumentsResponse
 */
class SupportedDocumentsResponseTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     * @covers ::getSupportedCountries
     */
    public function shouldHaveListOfSupportedCountries()
    {
        $supportedDocuments = new SupportedDocumentsResponse([
            'supported_countries' => [
                [],
                [],
                [],
            ],
        ]);

        $supportedCountries = $supportedDocuments->getSupportedCountries();

        $this->assertCount(3, $supportedCountries);
        $this->assertContainsOnlyInstancesOf(SupportedCountry::class, $supportedCountries);
    }
}
