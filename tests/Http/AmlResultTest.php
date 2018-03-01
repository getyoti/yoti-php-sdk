<?php

namespace YotiTest\Http;

use Yoti\Http\AmlResult;
use YotiTest\TestCase;

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

    public function testIsOnPepeList()
    {
        $this->assertTrue($this->amlResult->isOnPepList());
    }

    public function testIsOnFraudList()
    {
        $this->assertFalse($this->amlResult->isOnFraudList());
    }

    public function testIsOnWatchList()
    {
        $this->assertFalse($this->amlResult->isOnWatchList());
    }
}