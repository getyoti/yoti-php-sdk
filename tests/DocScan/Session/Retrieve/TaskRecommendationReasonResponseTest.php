<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\TaskRecommendationReasonResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\TaskRecommendationReasonResponse
 */
class TaskRecommendationReasonResponseTest extends TestCase
{
    private const SOME_VALUE = 'USER_ERROR';
    private const SOME_DETAIL = 'NO_DOCUMENT';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getValue
     * @covers ::getDetail
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'value' => self::SOME_VALUE,
            'detail' => self::SOME_DETAIL,
        ];

        $result = new TaskRecommendationReasonResponse($input);

        $this->assertEquals(self::SOME_VALUE, $result->getValue());
        $this->assertEquals(self::SOME_DETAIL, $result->getDetail());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldNotThrowExceptionWhenMissingValues()
    {
        $result = new TaskRecommendationReasonResponse([]);

        $this->assertNull($result->getValue());
        $this->assertNull($result->getDetail());
    }
}
