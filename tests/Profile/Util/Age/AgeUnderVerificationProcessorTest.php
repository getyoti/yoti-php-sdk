<?php

namespace YotiTest\Profile\Util\Age;

use Yoti\Profile\Attribute\AgeVerification;
use Yoti\Profile\Attribute\Attribute;
use Yoti\Profile\Util\Age\AgeUnderVerificationProcessor;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Profile\Util\Age\AgeUnderVerificationProcessor
 */
class AgeUnderVerificationProcessorTest extends TestCase
{
    /**
     * @covers ::process
     * @covers ::createAgeVerification
     * @covers \Yoti\Profile\Attribute\AgeVerification::getResult
     * @covers \Yoti\Profile\Attribute\AgeVerification::getCheckType
     * @covers \Yoti\Profile\Attribute\AgeVerification::getAge
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
     * @covers ::createAgeVerification
     * @covers \Yoti\Profile\Attribute\AgeVerification::getResult
     * @covers \Yoti\Profile\Attribute\AgeVerification::getCheckType
     * @covers \Yoti\Profile\Attribute\AgeVerification::getAge
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
     * @covers ::createAgeVerification
     */
    public function testWhenThereIsNotAgeUnderShouldReturnNull()
    {
        $ageAttribute = new Attribute('age_over:20', 'false', []);
        $ageUnderVerificationProcessor = new AgeUnderVerificationProcessor($ageAttribute);
        $this->assertNull($ageUnderVerificationProcessor->process());
    }
}
