<?php

namespace Yoti\Test\IDV\Session\Create\Check;

use Yoti\IDV\Session\Create\Check\RequestedDocumentAuthenticityCheck;
use Yoti\IDV\Session\Create\Check\RequestedDocumentAuthenticityCheckBuilder;
use Yoti\IDV\Session\Create\Check\RequestedDocumentAuthenticityCheckConfig;
use Yoti\IDV\Session\Create\Filters\DocumentFilter;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Create\Check\RequestedDocumentAuthenticityCheckBuilder
 */
class RequestedDocumentAuthenticityCheckBuilderTest extends TestCase
{
    /**
     * @test
     * @covers ::build
     * @covers \Yoti\IDV\Session\Create\Check\RequestedDocumentAuthenticityCheck::getConfig
     * @covers \Yoti\IDV\Session\Create\Check\RequestedDocumentAuthenticityCheck::getType
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
     * @covers \Yoti\IDV\Session\Create\Check\RequestedDocumentAuthenticityCheck::__construct
     * @covers \Yoti\IDV\Session\Create\Check\RequestedDocumentAuthenticityCheck::jsonSerialize
     * @covers \Yoti\IDV\Session\Create\Check\RequestedDocumentAuthenticityCheck::getType
     * @covers \Yoti\IDV\Session\Create\Check\RequestedDocumentAuthenticityCheck::getConfig
     */
    public function shouldJsonEncodeCorrectly()
    {
        $result = (new RequestedDocumentAuthenticityCheckBuilder())
            ->build();

        $expected = [
            'config' => (object)[],
            'type' => 'ID_DOCUMENT_AUTHENTICITY',
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }

    /**
     * @test
     * @covers \Yoti\IDV\Session\Create\Check\RequestedDocumentAuthenticityCheck::__toString
     */
    public function shouldCreateCorrectString()
    {
        $result = (new RequestedDocumentAuthenticityCheckBuilder())
            ->build();

        $expected = [
            'config' => (object)[],
            'type' => 'ID_DOCUMENT_AUTHENTICITY',
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), $result->__toString());
    }

    /**
     * @test
     * @covers ::withManualCheckAlways
     * @covers ::build
     * @covers \Yoti\IDV\Session\Create\Check\RequestedDocumentAuthenticityCheck::__construct
     */
    public function shouldJsonEncodeCorrectlyWithManualCheckAlways()
    {
        $result = (new RequestedDocumentAuthenticityCheckBuilder())
            ->withManualCheckAlways()
            ->build();

        $expected = [
            'config' => (object)[
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
     * @covers \Yoti\IDV\Session\Create\Check\RequestedDocumentAuthenticityCheck::__construct
     */
    public function shouldJsonEncodeCorrectlyWithManualCheckNever()
    {
        $result = (new RequestedDocumentAuthenticityCheckBuilder())
            ->withManualCheckNever()
            ->build();

        $expected = [
            'config' => (object)[
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
     * @covers \Yoti\IDV\Session\Create\Check\RequestedDocumentAuthenticityCheck::__construct
     */
    public function shouldJsonEncodeCorrectlyWithManualCheckFallback()
    {
        $result = (new RequestedDocumentAuthenticityCheckBuilder())
            ->withManualCheckFallback()
            ->build();

        $expected = [
            'config' => (object)[
                'manual_check' => 'FALLBACK',
            ],
            'type' => 'ID_DOCUMENT_AUTHENTICITY',
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }

    /**
     * @test
     * @covers ::withIssuingAuthoritySubCheck
     * @covers ::build
     * @covers \Yoti\IDV\Session\Create\Check\RequestedDocumentAuthenticityCheck::__construct
     * @covers \Yoti\IDV\Session\Create\Check\RequestedDocumentAuthenticityCheck::getConfig
     * @covers \Yoti\IDV\Session\Create\Check\RequestedDocumentAuthenticityCheckConfig::getIssuingAuthoritySubCheck
     */
    public function withIssuingAuthoritySubCheckShouldBuildDefaultObject()
    {
        $result = (new RequestedDocumentAuthenticityCheckBuilder())
            ->withIssuingAuthoritySubCheck()
            ->build();

        /** @var RequestedDocumentAuthenticityCheckConfig $config */
        $config = $result->getConfig();

        $this->assertTrue($config->getIssuingAuthoritySubCheck()->isRequested());
        $this->assertNull($config->getIssuingAuthoritySubCheck()->getFilter());
    }

    /**
     * @test
     * @covers ::withIssuingAuthoritySubCheckAndDocumentFilter
     * @covers ::build
     * @covers \Yoti\IDV\Session\Create\Check\RequestedDocumentAuthenticityCheck::__construct
     * @covers \Yoti\IDV\Session\Create\Check\RequestedDocumentAuthenticityCheck::getConfig
     * @covers \Yoti\IDV\Session\Create\Check\RequestedDocumentAuthenticityCheckConfig::getIssuingAuthoritySubCheck
     */
    public function withIssuingAuthoritySubCheckShouldAcceptDocumentFilter()
    {
        $documentFilterMock = $this->getMockForAbstractClass(DocumentFilter::class, ['type']);

        $result = (new RequestedDocumentAuthenticityCheckBuilder())
            ->withIssuingAuthoritySubCheckAndDocumentFilter($documentFilterMock)
            ->build();

        /** @var RequestedDocumentAuthenticityCheckConfig $config */
        $config = $result->getConfig();

        $this->assertTrue($config->getIssuingAuthoritySubCheck()->isRequested());
        $this->assertInstanceOf(DocumentFilter::class, $config->getIssuingAuthoritySubCheck()->getFilter());
    }
}
