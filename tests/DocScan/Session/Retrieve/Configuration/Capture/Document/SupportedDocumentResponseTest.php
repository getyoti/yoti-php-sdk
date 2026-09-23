<?php

namespace Yoti\Test\DocScan\Session\Retrieve\Configuration\Capture\Document;

use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Document\SupportedDocumentResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\Configuration\Capture\Document\SupportedDocumentResponse
 */
class SupportedDocumentResponseTest extends TestCase
{
    private const SOME_TYPE = 'SOME-TYPE';
    private const SOME_PROVIDERS = ['DIGILOCKER', 'EPHIL_ID_QR'];

    /**
     * @test
     * @covers ::__construct
     * @covers ::getType
     * @covers ::isStrictlyLatin
     * @covers ::getProviders
     */
    public function shouldBuildCorrectly(): void
    {
        $result = new SupportedDocumentResponse([
            'type' => self::SOME_TYPE,
            'is_strictly_latin' => true,
            'providers' => self::SOME_PROVIDERS,
        ]);

        $this->assertEquals(self::SOME_TYPE, $result->getType());
        $this->assertInstanceOf(SupportedDocumentResponse::class, $result);
        $this->assertTrue($result->isStrictlyLatin());
        $this->assertEquals(self::SOME_PROVIDERS, $result->getProviders());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getProviders
     */
    public function shouldReturnNullWhenProvidersNotPresent(): void
    {
        $result = new SupportedDocumentResponse(['type' => self::SOME_TYPE]);

        $this->assertNull($result->getProviders());
    }
}
