<?php

namespace YotiTest;

class AutoloadTest extends TestCase
{
    /**
     * Check that old namespace is aliased to new namespace with deprecation warning.
     *
     * @dataProvider deprecatedClassesDataProvider
     */
    public function testDeprecatedClassAliases($oldClass, $newClass)
    {
        $this->captureExpectedLogs();
        $this->assertInstanceOf($newClass, $this->createMock($oldClass));
        $this->assertLogContains("{$oldClass} has been replaced by {$newClass}");
    }

    /**
     * Provides map of old class namespaces to new.
     */
    public function deprecatedClassesDataProvider()
    {
        return [
            [ 'Yoti\ActivityDetails', \Yoti\Profile\ActivityDetails::class ],
            [ 'Yoti\Entity\AgeVerification', \Yoti\Profile\Attribute\AgeVerification::class ],
            [ 'Yoti\Entity\AmlCountry', \Yoti\Aml\Country::class ],
            [ 'Yoti\Entity\AmlProfile', \Yoti\Aml\Profile::class ],
            [ 'Yoti\Entity\Anchor', \Yoti\Profile\Attribute\Anchor::class ],
            [ 'Yoti\Entity\Attribute', \Yoti\Profile\Attribute\Attribute::class ],
            [ 'Yoti\Entity\AttributeDefinition', \Yoti\Profile\ExtraData\AttributeDefinition::class ],
            [ 'Yoti\Entity\AttributeIssuanceDetails', \Yoti\Profile\ExtraData\AttributeIssuanceDetails::class ],
            [ 'Yoti\Entity\ApplicationProfile', \Yoti\Profile\ApplicationProfile::class ],
            [ 'Yoti\Entity\BaseProfile', \Yoti\Profile\BaseProfile::class ],
            [ 'Yoti\Entity\DocumentDetails', \Yoti\Profile\Attribute\DocumentDetails::class ],
            [ 'Yoti\Entity\ExtraData', \Yoti\Profile\ExtraData\ExtraData::class ],
            [ 'Yoti\Entity\Image', \Yoti\Media\Image::class ],
            [ 'Yoti\Entity\MultiValue', \Yoti\Profile\Attribute\MultiValue::class ],
            [ 'Yoti\Entity\Profile', \Yoti\Profile\Profile::class ],
            [ 'Yoti\Entity\Receipt', \Yoti\Profile\Receipt::class ],
            [ 'Yoti\Entity\SignedTimeStamp', \Yoti\Profile\Attribute\SignedTimeStamp::class ],
        ];
    }
}
