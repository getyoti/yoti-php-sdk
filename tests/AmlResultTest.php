<?php

use Yoti\Http\AmlResult;

defined('AML_CHECK_RESULT_JSON') || define('AML_CHECK_RESULT_JSON', __DIR__ . '/../src/sample-data/aml-check-result.json');

class AmlResultTest extends PHPUnit\Framework\TestCase
{
    /**
     * @var Yoti\Http\AmlResult
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