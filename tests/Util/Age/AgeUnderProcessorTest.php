<?php
namespace YotiTest\Util\Age;

use YotiTest\TestCase;
use Yoti\Entity\Attribute;
use Yoti\Util\Age\AgeUnderProcessor;

class AgeUnderProcessorTest extends TestCase
{
    public $processor;

    public function setUp()
    {
        $ageAttribute = new Attribute('age_under:18', 'false', [], []);
        $this->processor = new AgeUnderProcessor($ageAttribute);
    }

    public function testProcessWithAgeUnder()
    {
        $result = $this->processor->process();
        $this->assertEquals('{"checkType":"age_under","age":18,"result":false}', json_encode($result));
    }

    public function testForAgeUnder20ShouldReturnTrue()
    {
        $ageAttribute = new Attribute('age_under:20', 'true', [], []);
        $processor = new AgeUnderProcessor($ageAttribute);
        $result = $processor->process();
        $this->assertEquals('{"checkType":"age_under","age":20,"result":true}', json_encode($result));
    }

    public function testWhenThereIsNotAgeUnderShouldReturnNull()
    {
        $ageAttribute = new Attribute('age_over:20', 'false', [], []);
        $processor = new AgeUnderProcessor($ageAttribute);
        $this->assertNull($processor->process());
    }
}