<?php

namespace YotiTest\Entity;

use Yoti\Entity\AttributeIssuanceDetails;
use Yoti\Entity\ExtraData;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Entity\ExtraData
 */
class ExtraDataTest extends TestCase
{
    /**
     * @covers ::getAttributeIssuanceDetails
     */
    public function testGetAttributeIssuanceDetails()
    {
        $expectedToken = 'someFirstToken';

        $extraData = new ExtraData([
            new AttributeIssuanceDetails($expectedToken),
            new AttributeIssuanceDetails('someSecondToken'),
        ]);

        $this->assertEquals(
            $expectedToken,
            $extraData->getAttributeIssuanceDetails()->getToken()
        );
    }

    /**
     * @covers ::getAttributeIssuanceDetails
     */
    public function testGetAttributeIssuanceDetailsEmpty()
    {
        $extraData = new ExtraData([]);

        $this->assertNull($extraData->getAttributeIssuanceDetails());
    }
}
