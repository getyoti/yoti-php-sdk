<?php
namespace YotiTest\Util\Age;

use YotiTest\TestCase;
use Yoti\Entity\Attribute;
use Yoti\Util\Age\AgeOverProcessor;

class AgeOverProcessorTest extends TestCase
{
    public $processor;

    public function setUp()
    {
        $ageAttribute = new Attribute('age_over:18', 'true', [], []);
        $this->processor = new AgeOverProcessor($ageAttribute);
    }

    public function testProcessWithAgeOver()
    {
        $ageData = $this->processor->process();
        $this->assertEquals('{"checkType":"age_over","age":18,"result":true}', json_encode($ageData));
    }

    public function testForAgeOver20ShouldReturnTrue()
    {
        $ageAttribute = new Attribute('age_over:20', 'true', [], []);
        $processor = new AgeOverProcessor($ageAttribute);
        $result = $processor->process();
        $this->assertEquals('{"checkType":"age_over","age":20,"result":true}', json_encode($result));
    }

    public function testWhenThereIsNotAgeOverShouldReturnNull()
    {
        $ageAttribute = new Attribute('age_under:20', 'false', [], []);
        $processor = new AgeOverProcessor($ageAttribute);
        $this->assertNull($processor->process());
    }
}