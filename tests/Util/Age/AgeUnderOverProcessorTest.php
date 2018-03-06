<?php

namespace YotiTest\Util\Age;

use YotiTest\TestCase;
use Yoti\Util\Age\AgeUnderOverProcessor;

class AgeUnderOverProcessorTest extends TestCase
{
    public $processor;
    public $dummyProfile;

    public function setUp()
    {
        $this->dummyProfile = [
            'family_name' => 'TestFamilyName',
            'given_names' => 'TestGivenName',
            'full_name'   => 'TestGivenName TestFamilyName',
            'date_of_birth' => '11-07-2017',
            'age_over:18'=> 'true',
            'gender' => 'Male',
            'nationality' => 'British',
            'phone_number' => '07856836932',
            'selfie' => '',
            'email_address' => 'test_email@yoti.com',
            'postal_address' => '130 Fenchurch Street London, EC3M 5DJ'
        ];

        $this->processor = new AgeUnderOverProcessor($this->dummyProfile);
    }

    public function testGetVerifiedAge()
    {
        $verifiedAge = $this->processor->getVerifiedAge('age_over:18');
        $this->assertEquals('over 18', $verifiedAge);
    }

    public function testProcess()
    {
        $ageData = $this->processor->process();
        $this->assertEquals('{"result":"true","verifiedAge":"over 18"}', json_encode($ageData));
    }

    public function testProcessWithAgeUnder()
    {
        $dummyProfile = $this->dummyProfile;
        unset($dummyProfile['age_over:18']);
        $dummyProfile['age_under:20'] = 'true';
        $processor = new AgeUnderOverProcessor($dummyProfile);
        $ageData = $processor->process();

        $this->assertEquals('{"result":"true","verifiedAge":"under 20"}', json_encode($ageData));
    }

    public function testGetAgeRow()
    {
        $ageRow = $this->processor->getAgeRow();
        $this->assertEquals('{"ageAttribute":"age_over:18","result":"true"}', json_encode($ageRow));
    }
}