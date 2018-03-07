<?php

namespace YotiTest\Util\Age;

use \YotiTest\TestCase;
use \Yoti\Util\Age\Processor;
use \Yoti\Util\Age\Condition;

class ProcessorTest extends TestCase
{
    /**
     * @var \Yoti\Util\Age\Processor
     */
    public $processor;

    public function setUp()
    {
        $dummyProfile = [
            'family_name' => 'TestFamilyName',
            'given_names' => 'TestGivenName',
            'full_name'   => 'TestGivenName TestFamilyName',
            'date_of_birth' => '01-01-1998',
            'age_over:18'=> 'true',
            'gender' => 'Male',
            'nationality' => 'British',
            'phone_number' => '07856836932',
            'selfie' => '',
            'email_address' => 'test_email@yoti.com',
            'postal_address' => '130 Fenchurch Street London, EC3M 5DJ'
        ];

        $this->processor = new Processor($dummyProfile);
    }

    public function testGetCondition()
    {
        $condition = $this->processor->getCondition();
        $this->assertInstanceOf(Condition::class, $condition);
    }

    public function testGetData()
    {
        $ageData = $this->processor->getAgeData();
        $this->assertEquals('{"result":"true","verifiedAge":"over 18"}', json_encode($ageData));
    }
}
