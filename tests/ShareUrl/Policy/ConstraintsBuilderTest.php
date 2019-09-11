<?php

namespace YotiTest\ShareUrl\Policy;

use Yoti\ShareUrl\Policy\ConstraintsBuilder;
use Yoti\ShareUrl\Policy\SourceConstraintBuilder;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\ShareUrl\Policy\ConstraintsBuilder
 */
class ConstraintsBuilderTest extends TestCase
{
    /**
     * @covers ::build
     * @covers ::withSourceConstraint
     */
    public function testBuild()
    {
        $constraints = (new ConstraintsBuilder())
            ->withSourceConstraint(
                (new SourceConstraintBuilder())
                    ->withPassport()
                    ->build()
            )
            ->build();

        $expectedJson = json_encode([
            [
                'type' => 'SOURCE',
                'preferred_sources' => [
                    'anchors' => [
                        [
                            'name' => 'PASSPORT',
                            'sub_type' => '',
                        ],
                    ],
                    'soft_preference' => false,
                ],
            ],
        ]);

        $this->assertEquals($expectedJson, json_encode($constraints));
        $this->assertEquals($expectedJson, $constraints);
    }
}
