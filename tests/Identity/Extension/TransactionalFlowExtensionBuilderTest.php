<?php

declare(strict_types=1);

namespace Yoti\Test\Identity\Extension;

use Yoti\Identity\Extension\TransactionalFlowExtensionBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Identity\Extension\TransactionalFlowExtensionBuilder
 */
class TransactionalFlowExtensionBuilderTest extends TestCase
{
    private const TYPE_TRANSACTIONAL_FLOW = 'TRANSACTIONAL_FLOW';

    /**
     * @covers ::build
     * @covers ::withContent
     */
    public function testBuild()
    {
        $someContent = (object)['some' => 'content'];

        $constraints = (new TransactionalFlowExtensionBuilder())
            ->withContent($someContent)
            ->build();

        $expectedJson = json_encode([
            'type' => self::TYPE_TRANSACTIONAL_FLOW,
            'content' => $someContent,
        ]);

        $this->assertEquals($expectedJson, json_encode($constraints));
    }
}
