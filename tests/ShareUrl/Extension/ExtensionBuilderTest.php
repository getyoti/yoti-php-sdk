<?php

namespace YotiTest\ShareUrl\Extension;

use Yoti\ShareUrl\Extension\ExtensionBuilder;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\ShareUrl\Policy\ExtensionBuilder
 */
class ExtensionBuilderTest extends TestCase
{
    /**
     * @covers ::build
     */
    public function testBuild()
    {
        $someType = 'some type';
        $someContent = 'some content';

        $constraints = (new ExtensionBuilder())
            ->withType($someType)
            ->withContent($someContent)
            ->build();

        $expectedJson = json_encode([
            'type' => $someType,
            'content' => $someContent,
        ]);

        $this->assertEquals($expectedJson, json_encode($constraints));
        $this->assertEquals($expectedJson, $constraints);
    }
}
