<?php

namespace Yoti\Test\DocScan\Session\Create\Check;

use Yoti\DocScan\Session\Create\Check\RequestedIdDocumentComparisonCheck;
use Yoti\DocScan\Session\Create\Check\RequestedIdDocumentComparisonCheckBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Check\RequestedIdDocumentComparisonCheckBuilder
 */
class RequestedIdDocumentComparisonCheckBuilderTest extends TestCase
{
    private const ID_DOCUMENT_COMPARISON = 'ID_DOCUMENT_COMPARISON';

    /**
     * @test
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedIdDocumentComparisonCheck::getConfig
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedIdDocumentComparisonCheck::getType
     */
    public function shouldCreateRequestedIdDocumentComparisonCheckCorrectly()
    {
        $result = (new RequestedIdDocumentComparisonCheckBuilder())
            ->build();

        $this->assertInstanceOf(RequestedIdDocumentComparisonCheck::class, $result);
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedIdDocumentComparisonCheck::jsonSerialize
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedIdDocumentComparisonCheck::getType
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedIdDocumentComparisonCheck::getConfig
     */
    public function shouldJsonEncodeCorrectly()
    {
        $result = (new RequestedIdDocumentComparisonCheckBuilder())
            ->build();

        $expected = [
            'type' => self::ID_DOCUMENT_COMPARISON,
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\Check\RequestedIdDocumentComparisonCheck::__toString
     */
    public function shouldCreateCorrectString()
    {
        $result = (new RequestedIdDocumentComparisonCheckBuilder())
            ->build();

        $expected = [
            'type' => self::ID_DOCUMENT_COMPARISON,
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), $result->__toString());
    }
}
