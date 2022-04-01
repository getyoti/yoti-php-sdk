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

    /**
     * @test
     * @covers ::__construct
     * @covers ::getType
     * @covers ::isStrictlyLatin
     */
    public function shouldBuildCorrectly(): void
    {
        $result = new SupportedDocumentResponse(['type' => self::SOME_TYPE, 'is_strictly_latin' => true]);

        $this->assertEquals(self::SOME_TYPE, $result->getType());
        $this->assertInstanceOf(SupportedDocumentResponse::class, $result);
        $this->assertTrue($result->isStrictlyLatin());
    }
}
