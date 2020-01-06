<?php

namespace YotiTest\Service\Aml;

use Yoti\Service\Aml\AmlResult;
use Yoti\Util\Json;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Service\Aml\AmlResult
 */
class AmlResultTest extends TestCase
{
    /**
     * @var \Yoti\Service\Aml\AmlResult
     */
    public $amlResult;

    public function setup()
    {
        $this->amlResult = new AmlResult(Json::decode(file_get_contents(AML_CHECK_RESULT_JSON)));
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
     * @expectedException \Yoti\Exception\AmlException
     * @expectedExceptionMessage Missing attributes from the result: on_pep_list,on_watch_list,on_watch_list
     */
    public function testMissingAttributes()
    {
        new AmlResult([]);
    }

    /**
     * @covers ::__toString
     */
    public function testToString()
    {
        $this->assertJsonStringEqualsJsonString(
            file_get_contents(AML_CHECK_RESULT_JSON),
            (string) $this->amlResult
        );
    }
}
