<?php

namespace YotiTest\Util\Age;

use YotiTest\TestCase;
use Yoti\Util\Age\Condition;

class ConditionTest extends TestCase
{
    public $condition;

    public function setUp()
    {
        $this->condition = new Condition('true', 'over 18');
    }

    public function testIsVerified()
    {
        $this->assertTrue($this->condition->isVerified());
    }

    public function testGetVerifiedAge()
    {
        $this->assertEquals('over 18', $this->condition->getVerifiedAge());
    }
}