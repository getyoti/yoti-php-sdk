<?php

namespace Yoti\Test\IDV\Session\Create\Check;

use Yoti\IDV\Session\Create\Check\RequestedIdDocumentComparisonCheck;
use Yoti\IDV\Session\Create\Check\RequestedIdDocumentComparisonCheckBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\IDV\Session\Create\Check\RequestedIdDocumentComparisonCheckBuilder
 */
class RequestedIdDocumentComparisonCheckBuilderTest extends TestCase
{
    private const ID_DOCUMENT_COMPARISON = 'ID_DOCUMENT_COMPARISON';

    /**
     * @test
     * @covers ::build
     * @covers \Yoti\IDV\Session\Create\Check\RequestedIdDocumentComparisonCheck::getConfig
     * @covers \Yoti\IDV\Session\Create\Check\RequestedIdDocumentComparisonCheck::getType
     */
    public function shouldCreateRequestedIdDocumentComparisonCheckCorrectly()
    {
        $result = (new RequestedIdDocumentComparisonCheckBuilder())
            ->build();

        $this->assertInstanceOf(RequestedIdDocumentComparisonCheck::class, $result);
    }

    /**
     * @test
     * @covers \Yoti\IDV\Session\Create\Check\RequestedIdDocumentComparisonCheck::jsonSerialize
     * @covers \Yoti\IDV\Session\Create\Check\RequestedIdDocumentComparisonCheck::getType
     * @covers \Yoti\IDV\Session\Create\Check\RequestedIdDocumentComparisonCheck::getConfig
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
     * @covers \Yoti\IDV\Session\Create\Check\RequestedIdDocumentComparisonCheck::__toString
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
