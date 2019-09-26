<?php

namespace YotiTest\Util\Age;

use YotiTest\TestCase;
use Yoti\Entity\Profile;
use Yoti\Entity\Attribute;
use Yoti\Entity\AgeVerification;
use Yoti\Util\Age\AgeVerificationConverter;

/**
 * @coversDefaultClass \Yoti\Util\Age\AgeVerificationConverter
 */
class AgeVerificationConverterTest extends TestCase
{
    /**
     * @covers ::getAgeVerificationsFromAttrsMap
     * @covers \Yoti\Entity\AgeVerification::__construct
     * @covers \Yoti\Entity\AgeVerification::getAttribute
     */
    public function testGetAgeVerificationsFromAttrsMap()
    {
        $ageAttribute = new Attribute('age_under:18', 'true', []);
        $ageVerificationConverter = new AgeVerificationConverter(['age_under:18' => $ageAttribute]);
        $ageVerifications = $ageVerificationConverter->getAgeVerificationsFromAttrsMap();
        $ageUnder18 = $ageVerifications['age_under:18'];

        $this->assertInstanceOf(AgeVerification::class, $ageUnder18);
        $this->assertEquals('age_under', $ageUnder18->getCheckType());
        $this->assertEquals(18, $ageUnder18->getAge());
        $this->assertTrue($ageUnder18->getResult());
        $this->assertInstanceOf(Attribute::class, $ageUnder18->getAttribute());
    }

    /**
     * @covers ::getAgeVerificationsFromAttrsMap
     */
    public function testMultipleAgeDerivations()
    {
        $profileData = [
            'age_under:18' => new Attribute('age_under:18', 'false', []),
            'age_over:50' => new Attribute('age_over:50', 'true', []),
            'age_breaker:50' => new Attribute('age_breaker:50', 'true', []),
        ];
        $ageVerificationConverter = new AgeVerificationConverter($profileData);
        $ageVerifications = $ageVerificationConverter->getAgeVerificationsFromAttrsMap();

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

    /**
     * @covers ::getAgeVerificationsFromAttrsMap
     */
    public function testShouldReturnEmptyAgeVerifications()
    {
        $profileData = [
            Profile::ATTR_GIVEN_NAMES => new Attribute(
                Profile::ATTR_GIVEN_NAMES,
                'TEST GIVEN NAMES',
                []
            ),
            Profile::ATTR_FAMILY_NAME => new Attribute(
                Profile::ATTR_FAMILY_NAME,
                'TEST FAMILY NAME',
                []
            ),
            'age_breaker:50' => new Attribute('age_breaker:50', 'true', []),
        ];
        $ageVerificationConverter = new AgeVerificationConverter($profileData);
        $ageVerifications = $ageVerificationConverter->getAgeVerificationsFromAttrsMap();
        $this->assertEmpty($ageVerifications);
    }
}
