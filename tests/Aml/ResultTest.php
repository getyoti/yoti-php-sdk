<?php

declare(strict_types=1);

namespace Yoti\Test\Aml;

use Yoti\Aml\Result;
use Yoti\Test\TestCase;
use Yoti\Test\TestData;
use Yoti\Util\Json;

/**
 * @coversDefaultClass \Yoti\Aml\Result
 */
class ResultTest extends TestCase
{
    /**
     * @var \Yoti\Aml\Result
     */
    public $amlResult;

    public function setup(): void
    {
        $this->amlResult = new Result(Json::decode(file_get_contents(TestData::AML_CHECK_RESULT_JSON)));
    }

    /**
     * @covers ::isOnPepList
     * @covers ::__construct
     * @covers ::setAttributes
     * @covers ::checkAttributes
     */
    public function testIsOnPepeList()
    {
        $this->assertTrue($this->amlResult->isOnPepList());
    }

    /**
     * @covers ::isOnFraudList
     * @covers ::__construct
     * @covers ::setAttributes
     * @covers ::checkAttributes
     */
    public function testIsOnFraudList()
    {
        $this->assertFalse($this->amlResult->isOnFraudList());
    }

    /**
     * @covers ::isOnWatchList
     * @covers ::__construct
     * @covers ::setAttributes
     * @covers ::checkAttributes
     */
    public function testIsOnWatchList()
    {
        $this->assertFalse($this->amlResult->isOnWatchList());
    }

    /**
     */
    public function testMissingAttributes()
    {
        $this->expectException(\Yoti\Exception\AmlException::class);
        $this->expectExceptionMessage('Missing attributes from the result: on_pep_list,on_watch_list,on_watch_list');

        new Result([]);
    }

    /**
     * @covers ::__toString
     */
    public function testToString()
    {
        $this->assertJsonStringEqualsJsonString(
            file_get_contents(TestData::AML_CHECK_RESULT_JSON),
            (string) $this->amlResult
        );
    }
}
