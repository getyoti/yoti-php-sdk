<?php

namespace Yoti\Test\Identity\Constraint;

use Yoti\Identity\Constraint\PreferredSources;
use Yoti\Identity\Policy\WantedAnchor;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Identity\Constraint\PreferredSources
 */
class PreferredSourcesTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::jsonSerialize
     * @covers ::getWantedAnchors
     * @covers ::isSoftPreference
     */
    public function testShouldBuildCorrectly()
    {
        $wantedAnchors = [
          new WantedAnchor('some'),
          new WantedAnchor('some_w'),
        ];

        $preferredSource = new PreferredSources(
            $wantedAnchors,
            true
        );

        $expected = [
            'anchors' => $wantedAnchors,
            'soft_preference' => true
        ];

        $this->assertInstanceOf(PreferredSources::class, $preferredSource);
        $this->assertEquals(json_encode($expected), json_encode($preferredSource));
        $this->assertEquals($wantedAnchors, $preferredSource->getWantedAnchors());
        $this->assertTrue($preferredSource->isSoftPreference());
    }
}
