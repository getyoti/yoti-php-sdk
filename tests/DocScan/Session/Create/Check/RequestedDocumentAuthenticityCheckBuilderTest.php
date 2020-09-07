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
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedDocumentAuthenticityCheck::__construct
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedDocumentAuthenticityCheck::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedDocumentAuthenticityCheck::getType
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedDocumentAuthenticityCheck::getConfig
     */
    public function shouldJsonEncodeCorrectly()
    {
        $result = (new RequestedDocumentAuthenticityCheckBuilder())
            ->build();

        $expected = [
            'config' => (object) [],
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
            'config' => (object) [],
            'type' => 'ID_DOCUMENT_AUTHENTICITY',
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), $result->__toString());
    }

    /**
     * @test
     * @covers ::withManualCheckAlways
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedDocumentAuthenticityCheck::__construct
     */
    public function shouldJsonEncodeCorrectlyWithManualCheckAlways()
    {
        $result = (new RequestedDocumentAuthenticityCheckBuilder())
            ->withManualCheckAlways()
            ->build();

        $expected = [
            'config' => (object) [
                'manual_check' => 'ALWAYS',
            ],
            'type' => 'ID_DOCUMENT_AUTHENTICITY',
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }

    /**
     * @test
     * @covers ::withManualCheckNever
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedDocumentAuthenticityCheck::__construct
     */
    public function shouldJsonEncodeCorrectlyWithManualCheckNever()
    {
        $result = (new RequestedDocumentAuthenticityCheckBuilder())
            ->withManualCheckNever()
            ->build();

        $expected = [
            'config' => (object) [
                'manual_check' => 'NEVER',
            ],
            'type' => 'ID_DOCUMENT_AUTHENTICITY',
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }

    /**
     * @test
     * @covers ::withManualCheckFallback
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedDocumentAuthenticityCheck::__construct
     */
    public function shouldJsonEncodeCorrectlyWithManualCheckFallback()
    {
        $result = (new RequestedDocumentAuthenticityCheckBuilder())
            ->withManualCheckFallback()
            ->build();

        $expected = [
            'config' => (object) [
                'manual_check' => 'FALLBACK',
            ],
            'type' => 'ID_DOCUMENT_AUTHENTICITY',
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }
}
