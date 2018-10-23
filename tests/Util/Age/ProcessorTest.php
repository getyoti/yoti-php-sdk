<?php

namespace YotiTest\Util\Age;

use Yoti\Entity\AgeVerification;
use YotiTest\TestCase;
use Yoti\Entity\Attribute;
use Yoti\Util\Age\Processor;

class ProcessorTest extends TestCase
{
    /**
     * @var \Yoti\Util\Age\Processor
     */
    public $ageProcessor;

    public function testGetFindAgeVerifications()
    {
        $ageAttribute = new Attribute('age_under:18', 'true', [], []);
        $ageProcessor = new Processor(['age_under:18'=> $ageAttribute]);
        $ageVerifications = $ageProcessor->getAgeVerificationsFromAttrsMap();
        $ageUnder18 = $ageVerifications['age_under:18'];

        $this->assertInstanceOf(AgeVerification::class, $ageUnder18);
        $this->assertEquals('age_under', $ageUnder18->getCheckType());
        $this->assertEquals(18, $ageUnder18->getAge());
        $this->assertTrue($ageUnder18->getResult());
        $this->assertInstanceOf(Attribute::class, $ageUnder18->getAttribute());
    }

    public function testMultipleAgeDerivations()
    {
        $profileData = [
            'age_under:18'=> new Attribute('age_under:18', 'false', [], []),
            'age_over:50'=> new Attribute('age_over:50', 'true', [], []),
            'age_breaker:50'=> new Attribute('age_breaker:50', 'true', [], []),
        ];
        $ageProcessor = new Processor($profileData);
        $ageVerifications = $ageProcessor->getAgeVerificationsFromAttrsMap();

        $this->assertArrayHasKey('age_under:18', $ageVerifications);
        $this->assertInstanceOf(AgeVerification::class, $ageVerifications['age_under:18']);
        $this->assertEquals('age_under', $ageVerifications['age_under:18']->getChecktype());
        $this->assertEquals(18, $ageVerifications['age_under:18']->getAge());
        $this->assertFalse($ageVerifications['age_under:18']->getResult());

        $this->assertArrayHasKey('age_over:50', $ageVerifications);
        $this->assertInstanceOf(AgeVerification::class, $ageVerifications['age_over:50']);
        $this->assertEquals('age_over', $ageVerifications['age_over:50']->getChecktype());
        $this->assertEquals(50, $ageVerifications['age_over:50']->getAge());
        $this->assertTrue($ageVerifications['age_over:50']->getResult());

        $this->assertArrayNotHasKey('age_breaker:50', $ageVerifications);
    }
}
