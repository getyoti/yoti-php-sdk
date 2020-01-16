<?php

declare(strict_types=1);

namespace YotiTest\Profile\Util\Age;

use Yoti\Profile\Attribute\AgeVerification;
use Yoti\Profile\Attribute\Attribute;
use Yoti\Profile\Util\Age\AgeOverVerificationProcessor;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Profile\Util\Age\AgeOverVerificationProcessor
 */
class AgeOverVerificationProcessorTest extends TestCase
{
    /**
     * @covers ::process
     * @covers ::createAgeVerification
     * @covers \Yoti\Profile\Attribute\AgeVerification::getResult
     * @covers \Yoti\Profile\Attribute\AgeVerification::getCheckType
     * @covers \Yoti\Profile\Attribute\AgeVerification::getAge
     */
    public function testProcessWithAgeOver()
    {
        $ageAttribute = new Attribute('age_over:18', 'true', []);
        $ageOverVerificationProcessor = new AgeOverVerificationProcessor($ageAttribute);
        $ageVerificationObj = $ageOverVerificationProcessor->process();
        $this->assertInstanceOf(AgeVerification::class, $ageVerificationObj);
        $this->assertTrue($ageVerificationObj->getResult());
        $this->assertEquals('age_over', $ageVerificationObj->getCheckType());
        $this->assertEquals(18, $ageVerificationObj->getAge());
    }

    /**
     * @covers ::process
     * @covers ::createAgeVerification
     * @covers \Yoti\Profile\Attribute\AgeVerification::getResult
     * @covers \Yoti\Profile\Attribute\AgeVerification::getCheckType
     * @covers \Yoti\Profile\Attribute\AgeVerification::getAge
     */
    public function testForAgeOver20ShouldReturnTrue()
    {
        $ageAttribute = new Attribute('age_over:20', 'true', []);
        $ageOverVerificationProcessor = new AgeOverVerificationProcessor($ageAttribute);
        $ageVerificationObj = $ageOverVerificationProcessor->process();
        $this->assertInstanceOf(AgeVerification::class, $ageVerificationObj);
        $this->assertTrue($ageVerificationObj->getResult());
        $this->assertEquals('age_over', $ageVerificationObj->getCheckType());
        $this->assertEquals(20, $ageVerificationObj->getAge());
    }

    /**
     * @covers ::process
     * @covers ::createAgeVerification
     */
    public function testWhenThereIsNotAgeOverShouldReturnNull()
    {
        $ageAttribute = new Attribute('age_under:20', 'false', []);
        $ageOverVerificationProcessor = new AgeOverVerificationProcessor($ageAttribute);
        $this->assertNull($ageOverVerificationProcessor->process());
    }
}
