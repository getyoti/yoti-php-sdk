<?php

namespace YotiTest\Util\Age;

use YotiTest\TestCase;
use Yoti\Util\Age\Condition;

class ConditionTest extends TestCase
{
    public $ageCondition;

    public function setUp()
    {
        //$this->ageCondition = new Condition('true', 'over 18');
    }

    public function testIsVerified()
    {
        //$this->assertTrue($this->ageCondition->isVerified());
    }

    public function testIsVerifiedWithFalseValue()
    {
        //$this->ageCondition->setResult('false');
        //$this->assertFalse($this->ageCondition->IsVerified());
    }

    public function testIsVerifiedWithEmptyAttribute()
    {
        //$ageCondition = new condition('', '');
        //$this->assertNull($ageCondition->isVerified());
    }

    public function testGetVerifiedAge()
    {
        //$this->assertEquals('over 18', $this->ageCondition->getVerifiedAge());
    }
}