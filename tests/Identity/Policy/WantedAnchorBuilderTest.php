<?php

declare(strict_types=1);

namespace Yoti\Test\Identity\Policy;

use Yoti\Identity\Policy\WantedAnchorBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Identity\Policy\WantedAnchorBuilder
 */
class WantedAnchorBuilderTest extends TestCase
{
    /**
     * @covers ::build
     * @covers ::withValue
     * @covers ::withSubType
     * @covers \Yoti\Identity\Policy\WantedAnchor::__construct
     * @covers \Yoti\Identity\Policy\WantedAnchor::jsonSerialize
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
    }
}
