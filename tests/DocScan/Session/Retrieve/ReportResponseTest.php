<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Retrieve;

use Yoti\DocScan\Session\Retrieve\ReportResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\ReportResponse
 */
class ReportResponseTest extends TestCase
{

    /**
     * @test
     * @covers ::__construct
     * @covers ::getRecommendation
     * @covers ::getBreakdown
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'recommendation' => [],
            'breakdown' => [
                [ 'someKey' => 'someValue' ],
                [ 'someOtherKey' => 'someOtherValue' ],
            ],
        ];

        $result = new ReportResponse($input);

        $this->assertNotNull($result->getRecommendation());
        $this->assertCount(2, $result->getBreakdown());
    }

    /**
     * @test
     * @covers ::__construct
     */
    public function shouldNotThrowExceptionWhenMissingValues()
    {
        $result = new ReportResponse([]);

        $this->assertNull($result->getRecommendation());
        $this->assertCount(0, $result->getBreakdown());
    }
}
