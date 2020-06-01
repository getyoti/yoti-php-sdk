<?php

declare(strict_types=1);

namespace Yoti\Test\ShareUrl\Extension;

use Yoti\Profile\ExtraData\AttributeDefinition;
use Yoti\ShareUrl\Extension\ThirdPartyAttributeContent;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\ShareUrl\Extension\ThirdPartyAttributeContent
 */
class ThirdPartyAttributeContentTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::jsonSerialize
     * @covers ::__toString
     */
    public function testJsonSerialize()
    {
        $someDefinition = 'some definition';

        $thirdPartyAttributeContent = new ThirdPartyAttributeContent(
            new \DateTime('2019-12-02T12:00:00.123Z'),
            [
                new AttributeDefinition($someDefinition),
            ]
        );

        $expectedJson = json_encode([
            'expiry_date' => '2019-12-02T12:00:00.123+00:00',
            'definitions' => [
                [
                    'name' => $someDefinition,
                ],
            ],
        ]);

        $this->assertEquals($expectedJson, json_encode($thirdPartyAttributeContent));
        $this->assertEquals($expectedJson, $thirdPartyAttributeContent);
    }
}
