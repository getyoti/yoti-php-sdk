<?php

namespace Yoti\Test\DocScan\Session\Retrieve\Configuration\Capture;

use Yoti\DocScan\Constants;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\RequiredResourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\EndUserAllowedSourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\IbvAllowedSourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\RelyingBusinessAllowedSourceResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Source\UnknownAllowedSourceResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\Configuration\Capture\RequiredResourceResponse
 */
class RequiredResourceResponseTest extends TestCase
{
    private const SOME_TYPE = 'SOME_TYPE';
    private const SOME_ID = 'SOME_ID';
    private const SOME_STATE = 'SOME_STATE';
    private const SOME_ALLOWED_SOURCES = [
        [
            'type' => Constants::END_USER,
        ],
        [
            'type' => Constants::RELYING_BUSINESS,
        ],
        [
            'type' => Constants::IBV,
        ],
    ];

    /**
     * @test
     * @covers ::__construct
     * @covers ::getType
     * @covers ::getId
     * @covers ::getState
     * @covers ::getAllowedSources
     * @covers ::createAllowedSourceFromArray
     * @covers ::isRelyingBusinessAllowed
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'type' => self::SOME_TYPE,
            'id' => self::SOME_ID,
            'state' => self::SOME_STATE,
            'allowed_sources' => self::SOME_ALLOWED_SOURCES,
        ];

        $result = new RequiredResourceResponse($input);

        $this->assertEquals(self::SOME_TYPE, $result->getType());
        $this->assertEquals(self::SOME_ID, $result->getId());
        $this->assertEquals(self::SOME_STATE, $result->getState());

        $this->assertCount(3, $result->getAllowedSources());

        $this->assertInstanceOf(EndUserAllowedSourceResponse::class, $result->getAllowedSources()[0]);
        $this->assertInstanceOf(RelyingBusinessAllowedSourceResponse::class, $result->getAllowedSources()[1]);
        $this->assertInstanceOf(IbvAllowedSourceResponse::class, $result->getAllowedSources()[2]);

        $this->assertTrue($result->isRelyingBusinessAllowed());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::isRelyingBusinessAllowed
     * @covers ::createAllowedSourceFromArray
     */
    public function shouldHaveIsRelyingBusinessFalseAndUnknownSource()
    {
        $input = [
            'type' => self::SOME_TYPE,
            'id' => self::SOME_ID,
            'state' => self::SOME_STATE,
            'allowed_sources' => [
                [
                    'type' => 'Unknown',
                ],
            ]
        ];

        $result = new RequiredResourceResponse($input);

        $this->assertFalse($result->isRelyingBusinessAllowed());
        $this->assertInstanceOf(UnknownAllowedSourceResponse::class, $result->getAllowedSources()[0]);
    }
}
