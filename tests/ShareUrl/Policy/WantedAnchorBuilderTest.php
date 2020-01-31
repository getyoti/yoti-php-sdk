<?php

declare(strict_types=1);

namespace Yoti\Test\ShareUrl\Policy;

use Yoti\ShareUrl\Policy\WantedAnchorBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\ShareUrl\Policy\WantedAnchorBuilder
 */
class WantedAnchorBuilderTest extends TestCase
{
    /**
     * @covers ::build
     * @covers ::withValue
     * @covers ::withSubType
     * @covers \Yoti\ShareUrl\Policy\WantedAnchor::__construct
     * @covers \Yoti\ShareUrl\Policy\WantedAnchor::__toString
     * @covers \Yoti\ShareUrl\Policy\WantedAnchor::jsonSerialize
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
