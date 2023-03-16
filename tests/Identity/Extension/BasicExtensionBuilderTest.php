<?php

declare(strict_types=1);

namespace Yoti\Test\Identity\Extension;

use Yoti\Identity\Extension\BasicExtensionBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Identity\Extension\BasicExtensionBuilder
 */
class BasicExtensionBuilderTest extends TestCase
{
    /**
     * @covers ::build
     * @covers ::withType
     * @covers ::withContent
     * @covers \Yoti\Identity\Extension\Extension::__construct
     * @covers \Yoti\Identity\Extension\Extension::jsonSerialize
     * @covers \Yoti\Identity\Extension\ExtensionBuilderInterface::build
     */
    public function testBuild()
    {
        $someType = 'some type';
        $someContent = 'some content';

        $constraints = (new BasicExtensionBuilder())
            ->withType($someType)
            ->withContent($someContent)
            ->build();

        $expectedJson = json_encode([
            'type' => $someType,
            'content' => $someContent,
        ]);

        $this->assertEquals($expectedJson, json_encode($constraints));
    }
}
