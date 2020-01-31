<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\RecommendationResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\RecommendationResponse
 */
class RecommendationResponseTest extends TestCase
{

    private const SOME_VALUE = 'someValue';
    private const SOME_REASON = 'someReason';
    private const SOME_RECOVERY_SUGGESTION = 'someRecoverySuggestion';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getValue
     * @covers ::getReason
     * @covers ::getRecoverySuggestion
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'value' => self::SOME_VALUE,
            'reason' => self::SOME_REASON,
            'recovery_suggestion' => self::SOME_RECOVERY_SUGGESTION,
        ];

        $result = new RecommendationResponse($input);

        $this->assertEquals(self::SOME_VALUE, $result->getValue());
        $this->assertEquals(self::SOME_REASON, $result->getReason());
        $this->assertEquals(self::SOME_RECOVERY_SUGGESTION, $result->getRecoverySuggestion());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldNotThrowExceptionWhenMissingValue()
    {
        $result = new RecommendationResponse([]);

        $this->assertNull($result->getValue());
        $this->assertNull($result->getReason());
        $this->assertNull($result->getRecoverySuggestion());
    }
}
