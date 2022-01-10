<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Support;

use Yoti\DocScan\Support\SupportedDocument;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Support\SupportedDocument
 */
class SupportedDocumentTest extends TestCase
{
    private const SOME_DOCUMENT_TYPE = 'some-document-type';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getType
     */
    public function shouldHaveType()
    {
        $supportedDocument = new SupportedDocument([
            'type' => self::SOME_DOCUMENT_TYPE,
        ]);

        $this->assertEquals(self::SOME_DOCUMENT_TYPE, $supportedDocument->getType());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getType
     * @covers ::getIsStrictlyLatin
     */
    public function shouldHaveIsStrictlyLatinFlag()
    {
        $supportedDocument = new SupportedDocument([
            'type' => self::SOME_DOCUMENT_TYPE,
            'is_strictly_latin' => true,
        ]);

        $this->assertEquals(self::SOME_DOCUMENT_TYPE, $supportedDocument->getType());
        $this->assertTrue($supportedDocument->getIsStrictlyLatin());
    }
}
