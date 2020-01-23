<?php

declare(strict_types=1);

namespace YotiTest\Profile\ExtraData;

use Yoti\Profile\ExtraData;
use Yoti\Profile\ExtraData\AttributeIssuanceDetails;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Profile\ExtraData
 */
class ExtraDataTest extends TestCase
{
    /**
     * @covers ::getAttributeIssuanceDetails
     * @covers ::setAttributeIssuanceDetails
     * @covers ::__construct
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
     * @covers ::setAttributeIssuanceDetails
     * @covers ::__construct
     */
    public function testGetAttributeIssuanceDetailsEmpty()
    {
        $extraData = new ExtraData([]);

        $this->assertNull($extraData->getAttributeIssuanceDetails());
    }
}
