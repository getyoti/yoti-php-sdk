<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\BreakdownResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\BreakdownResponse
 */
class BreakdownResponseTest extends TestCase
{

    private const SOME_SUB_CHECK = 'someSubCheck';
    private const SOME_RESULT = 'someResult';
    private const SOME_DETAILS = [
        [
            'name' => 'someName',
            'value' => 'someValue'
        ],
        [
            'name' => 'someSecondName',
            'value' => 'someSecondValue'
        ],
    ];

    /**
     * @test
     * @covers ::__construct
     * @covers ::getSubCheck
     * @covers ::getResult
     * @covers ::getDetails
     * @covers \Yoti\DocScan\Session\Retrieve\DetailsResponse::__construct
     * @covers \Yoti\DocScan\Session\Retrieve\DetailsResponse::getName
     * @covers \Yoti\DocScan\Session\Retrieve\DetailsResponse::getValue
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'sub_check' => self::SOME_SUB_CHECK,
            'result' => self::SOME_RESULT,
            'details' => self::SOME_DETAILS,
        ];

        $result = new BreakdownResponse($input);

        $this->assertEquals(self::SOME_SUB_CHECK, $result->getSubCheck());
        $this->assertEquals(self::SOME_RESULT, $result->getResult());

        $details = $result->getDetails();
        for ($i = 0; $i < count(self::SOME_DETAILS); $i++) {
            $detailResponse = $details[$i];
            $this->assertEquals(self::SOME_DETAILS[$i]['name'], $detailResponse->getName());
            $this->assertEquals(self::SOME_DETAILS[$i]['value'], $detailResponse->getValue());
        }
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getSubCheck
     * @covers ::getResult
     * @covers ::getDetails
     */
    public function shouldNotThrowExceptionWhenValuesAreMissing()
    {
        $input = [];

        $result = new BreakdownResponse($input);

        $this->assertNull($result->getSubCheck());
        $this->assertNull($result->getResult());
        $this->assertCount(0, $result->getDetails());
    }
}
