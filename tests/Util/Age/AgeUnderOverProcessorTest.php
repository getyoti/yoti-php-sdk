<?php

namespace YotiTest\Util\Age;

use YotiTest\TestCase;
use Yoti\Entity\Attribute;
use Yoti\Util\Age\AgeUnderOverProcessor;

class AgeUnderOverProcessorTest extends TestCase
{
    public $processor;
    public $dummyProfile;

    public function setUp()
    {
       /*$this->dummyProfile = [
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
        ];*/

        $ageAttribute = new Attribute('age_under:18', 'false', [], []);
        $this->processor = new AgeUnderOverProcessor($ageAttribute);
    }

    public function testGetVerifiedAge()
    {
        //$verifiedAge = $this->processor->getVerifiedAge('age_over:18');
        //$this->assertEquals('over 18', $verifiedAge);
    }

    public function testParseAttribute()
    {
        $ageData = $this->processor->parseAttribute();
        $this->assertEquals('{"checkType":"age_under","age":18,"result":false}', json_encode($ageData));
    }

    public function testProcessWithAgeUnder()
    {
        $ageData = $this->processor->parseAttribute();
        $this->assertEquals('{"checkType":"age_under","age":18,"result":false}', json_encode($ageData));
    }

    public function testGetAgeRow()
    {
        //$ageRow = $this->processor->getAgeRow();
        //$this->assertEquals('{"ageAttribute":"age_over:18","result":"true"}', json_encode($ageRow));
    }
}