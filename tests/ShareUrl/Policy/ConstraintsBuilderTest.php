<?php

declare(strict_types=1);

namespace Yoti\Test\ShareUrl\Policy;

use Yoti\ShareUrl\Policy\ConstraintsBuilder;
use Yoti\ShareUrl\Policy\SourceConstraintBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\ShareUrl\Policy\ConstraintsBuilder
 */
class ConstraintsBuilderTest extends TestCase
{
    /**
     * @covers ::build
     * @covers ::withSourceConstraint
     * @covers \Yoti\ShareUrl\Policy\Constraints::__construct
     * @covers \Yoti\ShareUrl\Policy\Constraints::__toString
     * @covers \Yoti\ShareUrl\Policy\Constraints::jsonSerialize
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
