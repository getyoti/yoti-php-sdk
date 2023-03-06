<?php

declare(strict_types=1);

namespace Yoti\Test\Identity\Extension;

use Yoti\Identity\Extension\ThirdPartyAttributeContent;
use Yoti\Profile\ExtraData\AttributeDefinition;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Identity\Extension\ThirdPartyAttributeContent
 */
class ThirdPartyAttributeContentTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::jsonSerialize
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
    }
}
