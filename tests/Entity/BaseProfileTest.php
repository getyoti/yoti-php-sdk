<?php

namespace YotiTest\Entity;

use YotiTest\TestCase;
use Yoti\Entity\Attribute;
use Yoti\Entity\BaseProfile;

/**
 * @coversDefaultClass \Yoti\Entity\BaseProfile
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
