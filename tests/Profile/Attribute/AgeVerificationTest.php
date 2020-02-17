<?php

declare(strict_types=1);

namespace Yoti\Test\Profile\Attribute;

use Yoti\Profile\Attribute;
use Yoti\Profile\Attribute\AgeVerification;
use Yoti\Profile\UserProfile;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Profile\Attribute\AgeVerification
 */
class AgeVerificationTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getCheckType
     * @covers ::getAge
     * @covers ::getresult
     * @covers ::getAttribute
     *
     * @dataProvider validAgeAttributeDataProvider
     */
    public function testValidAgeVerifications($attribute, $checkType, $age, $result)
    {
        $ageVerification = new AgeVerification($attribute);

        $this->assertEquals($checkType, $ageVerification->getCheckType());
        $this->assertEquals($age, $ageVerification->getAge());
        $this->assertEquals($result, $ageVerification->getresult());
        $this->assertSame($attribute, $ageVerification->getAttribute());
    }

    /**
     * Provides valid age verification attributes and expected age verification values.
     */
    public function validAgeAttributeDataProvider()
    {
        return [
            [
                new Attribute(
                    UserProfile::AGE_UNDER . '18',
                    'false',
                    []
                ),
                'age_under',
                18,
                false
            ],
            [
                new Attribute(
                    UserProfile::AGE_OVER . '35',
                    'true',
                    []
                ),
                'age_over',
                35,
                true
            ]
        ];
    }

    /**
     * @covers ::__construct
     */
    public function testInvalidAgeVerification()
    {
        $someAttributeName = 'some-invalid-age-verification';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            "'attribute.name' value '%s' does not match format '/^[^:]+:(?!.*:)[0-9]+$/'",
            $someAttributeName
        ));

        new AgeVerification(
            new Attribute(
                $someAttributeName,
                'false',
                []
            )
        );
    }
}
