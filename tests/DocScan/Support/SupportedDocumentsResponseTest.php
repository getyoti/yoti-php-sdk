<?php

declare(strict_types=1);

namespace Yoti\Test\IDV\Support;

use Yoti\IDV\Support\SupportedCountry;
use Yoti\IDV\Support\SupportedDocumentsResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Support\SupportedDocumentsResponse
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
