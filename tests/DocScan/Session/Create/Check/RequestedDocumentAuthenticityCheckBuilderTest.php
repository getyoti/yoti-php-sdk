<?php

namespace Yoti\Test\DocScan\Session\Create\Check;

use Yoti\DocScan\Session\Create\Check\RequestedDocumentAuthenticityCheck;
use Yoti\DocScan\Session\Create\Check\RequestedDocumentAuthenticityCheckBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Check\RequestedDocumentAuthenticityCheckBuilder
 */
class RequestedDocumentAuthenticityCheckBuilderTest extends TestCase
{

    /**
     * @test
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedDocumentAuthenticityCheck::getConfig
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedDocumentAuthenticityCheck::getType
     */
    public function shouldCreateRequestedDocumentAuthenticityCheckCorrectly()
    {
        $result = (new RequestedDocumentAuthenticityCheckBuilder())
            ->build();

        $this->assertInstanceOf(RequestedDocumentAuthenticityCheck::class, $result);
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedDocumentAuthenticityCheck::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedDocumentAuthenticityCheck::getType
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedDocumentAuthenticityCheck::getConfig
     */
    public function shouldJsonEncodeCorrectly()
    {
        $result = (new RequestedDocumentAuthenticityCheckBuilder())
            ->build();

        $expected = [
            'type' => 'ID_DOCUMENT_AUTHENTICITY',
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedDocumentAuthenticityCheck::__toString
     */
    public function shouldCreateCorrectString()
    {
        $result = (new RequestedDocumentAuthenticityCheckBuilder())
            ->build();

        $expected = [
            'type' => 'ID_DOCUMENT_AUTHENTICITY',
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), $result->__toString());
    }
}
