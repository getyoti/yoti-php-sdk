<?php

namespace YotiTest\Http;

use Yoti\Http\AmlResult;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Http\AmlResult
 */
class AmlResultTest extends TestCase
{
    /**
     * @var \Yoti\Http\AmlResult
     */
    public $amlResult;

    public function setup()
    {
        $resultArr = json_decode(file_get_contents(AML_CHECK_RESULT_JSON), TRUE);
        $this->amlResult = new AmlResult($resultArr);
    }

    /**
     * @covers ::isOnPepList
     */
    public function testIsOnPepeList()
    {
        $this->assertTrue($this->amlResult->isOnPepList());
    }

    /**
     * @covers ::isOnFraudList
     */
    public function testIsOnFraudList()
    {
        $this->assertFalse($this->amlResult->isOnFraudList());
    }

    /**
     * @covers ::isOnWatchList
     */
    public function testIsOnWatchList()
    {
        $this->assertFalse($this->amlResult->isOnWatchList());
    }
}