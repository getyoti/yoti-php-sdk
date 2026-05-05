<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\TaskRecommendationReasonResponse;
use Yoti\DocScan\Session\Retrieve\TaskRecommendationResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\TaskRecommendationResponse
 */
class TaskRecommendationResponseTest extends TestCase
{
    private const SOME_VALUE = 'MUST_TRY_AGAIN';
    private const SOME_REASON_VALUE = 'USER_ERROR';
    private const SOME_REASON_DETAIL = 'NO_DOCUMENT';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getValue
     * @covers ::getReason
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'value' => self::SOME_VALUE,
            'reason' => [
                'value' => self::SOME_REASON_VALUE,
                'detail' => self::SOME_REASON_DETAIL,
            ],
        ];

        $result = new TaskRecommendationResponse($input);

        $this->assertEquals(self::SOME_VALUE, $result->getValue());
        $this->assertInstanceOf(TaskRecommendationReasonResponse::class, $result->getReason());
        $this->assertEquals(self::SOME_REASON_VALUE, $result->getReason()->getValue());
        $this->assertEquals(self::SOME_REASON_DETAIL, $result->getReason()->getDetail());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldNotThrowExceptionWhenMissingValues()
    {
        $result = new TaskRecommendationResponse([]);

        $this->assertNull($result->getValue());
        $this->assertNull($result->getReason());
    }
}
