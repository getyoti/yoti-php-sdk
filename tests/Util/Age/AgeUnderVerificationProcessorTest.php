<?php
namespace YotiTest\Util\Age;

use YotiTest\TestCase;
use Yoti\Entity\Attribute;
use Yoti\Entity\AgeVerification;
use Yoti\Util\Age\AgeUnderVerificationProcessor;

/**
 * @coversDefaultClass \Yoti\Util\Age\AgeUnderVerificationProcessor
 */
class AgeUnderVerificationProcessorTest extends TestCase
{
    /**
     * @covers ::process
     * @covers \Yoti\Entity\AgeVerification::getResult
     * @covers \Yoti\Entity\AgeVerification::getCheckType
     * @covers \Yoti\Entity\AgeVerification::getAge
     */
    public function testProcessWithAgeUnder()
    {
        $ageAttribute = new Attribute('age_under:18', 'false', []);
        $ageUnderVerificationProcessor = new AgeUnderVerificationProcessor($ageAttribute);
        $ageVerificationObj = $ageUnderVerificationProcessor->process();
        $this->assertInstanceOf(AgeVerification::class, $ageVerificationObj);
        $this->assertFalse($ageVerificationObj->getResult());
        $this->assertEquals('age_under', $ageVerificationObj->getChecktype());
        $this->assertEquals(18, $ageVerificationObj->getAge());
    }

    /**
     * @covers ::process
     * @covers \Yoti\Entity\AgeVerification::getResult
     * @covers \Yoti\Entity\AgeVerification::getCheckType
     * @covers \Yoti\Entity\AgeVerification::getAge
     */
    public function testForAgeUnder20ShouldReturnTrue()
    {
        $ageAttribute = new Attribute('age_under:20', 'true', []);
        $ageUnderVerificationProcessor = new AgeUnderVerificationProcessor($ageAttribute);
        $ageVerificationObj = $ageUnderVerificationProcessor->process();
        $this->assertInstanceOf(AgeVerification::class, $ageVerificationObj);
        $this->assertTrue($ageVerificationObj->getResult());
        $this->assertEquals('age_under', $ageVerificationObj->getCheckType());
        $this->assertEquals(20, $ageVerificationObj->getAge());
    }

    /**
     * @covers ::process
     */
    public function testWhenThereIsNotAgeUnderShouldReturnNull()
    {
        $ageAttribute = new Attribute('age_over:20', 'false', []);
        $ageUnderVerificationProcessor = new AgeUnderVerificationProcessor($ageAttribute);
        $this->assertNull($ageUnderVerificationProcessor->process());
    }
}
