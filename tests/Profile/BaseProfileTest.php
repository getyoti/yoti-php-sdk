<?php

namespace YotiTest\Profile;

use YotiTest\TestCase;
use Yoti\Profile\Attribute\Attribute;
use Yoti\Profile\BaseProfile;

/**
 * @coversDefaultClass \Yoti\Profile\BaseProfile
 */
class BaseProfileTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getProfileAttribute
     */
    public function testAttributeGetters()
    {
        $someAttributeName = 'some_attribute';
        $someAttribute = $this->createMock(Attribute::class);

        $someInvalidAttributeName = 'some_invalid_attribute';

        $someProfileData = [
            $someAttributeName => $someAttribute,
            $someInvalidAttributeName => 'invalid',
        ];

        $baseProfile = new BaseProfile($someProfileData);

        $this->assertSame($someAttribute, $baseProfile->getProfileAttribute($someAttributeName));
        $this->assertNull($baseProfile->getProfileAttribute($someInvalidAttributeName));
        $this->assertNull($baseProfile->getProfileAttribute('some_missing_attribute'));
    }
}
