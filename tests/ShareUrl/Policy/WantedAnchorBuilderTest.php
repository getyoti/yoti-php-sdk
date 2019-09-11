<?php

namespace YotiTest\ShareUrl\Policy;

use Yoti\ShareUrl\Policy\WantedAnchorBuilder;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\ShareUrl\Policy\WantedAnchorBuilder
 */
class WantedAnchorBuilderTest extends TestCase
{
    /**
     * @covers ::build
     * @covers ::withName
     * @covers ::withSubType
     */
    public function testBuild()
    {
        $someName = 'some name';
        $someSubType = 'some sub type';

        $wantedAnchor = (new WantedAnchorBuilder())
            ->withValue($someName)
            ->withSubType($someSubType)
            ->build();

        $expectedJsonData = [
            'name' => $someName,
            'sub_type' => $someSubType,
        ];

        $this->assertEquals(json_encode($expectedJsonData), json_encode($wantedAnchor));
        $this->assertEquals(json_encode($expectedJsonData), $wantedAnchor);
    }
}
