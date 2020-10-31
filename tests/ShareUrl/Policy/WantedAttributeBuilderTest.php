<?php

declare(strict_types=1);

namespace Yoti\Test\ShareUrl\Policy;

use Yoti\ShareUrl\Policy\ConstraintsBuilder;
use Yoti\ShareUrl\Policy\SourceConstraintBuilder;
use Yoti\ShareUrl\Policy\WantedAttributeBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\ShareUrl\Policy\WantedAttributeBuilder
 */
class WantedAttributeBuilderTest extends TestCase
{
    /**
     * @covers ::build
     * @covers ::withName
     * @covers ::withDerivation
     * @covers \Yoti\ShareUrl\Policy\WantedAttribute::__construct
     * @covers \Yoti\ShareUrl\Policy\WantedAttribute::__toString
     * @covers \Yoti\ShareUrl\Policy\WantedAttribute::jsonSerialize
     * @covers \Yoti\ShareUrl\Policy\WantedAttribute::getDerivation
     * @covers \Yoti\ShareUrl\Policy\WantedAttribute::getName
     */
    public function testBuild()
    {
        $someName = 'some name';
        $someDerivation = 'some derivation';

        $wantedAttribute = (new WantedAttributeBuilder())
            ->withName($someName)
            ->withDerivation($someDerivation)
            ->build();

        $expectedJsonData = [
            'name' => $someName,
            'optional' => false,
            'derivation' => $someDerivation,
        ];

        $this->assertEquals(json_encode($expectedJsonData), json_encode($wantedAttribute));
        $this->assertEquals(json_encode($expectedJsonData), $wantedAttribute);
    }

    /**
     * @covers ::build
     * @covers ::withName
     */
    public function testEmptyName()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('name cannot be empty');

        (new WantedAttributeBuilder())
            ->withName('')
            ->build();
    }

    /**
     * @covers ::build
     * @covers ::withName
     */
    public function testNonStringName()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(sprintf('%s::withName()', WantedAttributeBuilder::class));

        (new WantedAttributeBuilder())
            ->withName(['some array'])
            ->build();
    }

    /**
     * @covers ::withAcceptSelfAsserted
     * @covers \Yoti\ShareUrl\Policy\WantedAttribute::__construct
     * @covers \Yoti\ShareUrl\Policy\WantedAttribute::jsonSerialize
     * @covers \Yoti\ShareUrl\Policy\WantedAttribute::getAcceptSelfAsserted
     */
    public function testAcceptSelfAsserted()
    {
        $someName = 'some name';

        $expectedJsonData = [
            'name' => $someName,
            'optional' => false,
            'accept_self_asserted' => true,
        ];

        $wantedAttributeDefault = (new WantedAttributeBuilder())
            ->withName($someName)
            ->withAcceptSelfAsserted()
            ->build();

        $this->assertEquals(json_encode($expectedJsonData), json_encode($wantedAttributeDefault));
        $this->assertEquals(json_encode($expectedJsonData), $wantedAttributeDefault);

        $wantedAttribute = (new WantedAttributeBuilder())
            ->withName($someName)
            ->withAcceptSelfAsserted(true)
            ->build();

        $this->assertEquals(json_encode($expectedJsonData), json_encode($wantedAttribute));
        $this->assertEquals(json_encode($expectedJsonData), $wantedAttribute);
    }

    /**
     * @covers ::withAcceptSelfAsserted
     * @covers \Yoti\ShareUrl\Policy\WantedAttribute::__construct
     * @covers \Yoti\ShareUrl\Policy\WantedAttribute::jsonSerialize
     * @covers \Yoti\ShareUrl\Policy\WantedAttribute::getAcceptSelfAsserted
     */
    public function testWithoutAcceptSelfAsserted()
    {
        $someName = 'some name';

        $expectedJsonData = [
            'name' => $someName,
            'optional' => false,
            'accept_self_asserted' => false,
        ];

        $wantedAttribute = (new WantedAttributeBuilder())
            ->withName($someName)
            ->withAcceptSelfAsserted(false)
            ->build();

        $this->assertEquals(json_encode($expectedJsonData), json_encode($wantedAttribute));
        $this->assertEquals(json_encode($expectedJsonData), $wantedAttribute);
    }

    /**
     * @covers ::withConstraints
     * @covers \Yoti\ShareUrl\Policy\WantedAttribute::jsonSerialize
     * @covers \Yoti\ShareUrl\Policy\WantedAttribute::getConstraints
     */
    public function testWithConstraints()
    {
        $someName = 'some name';

        $sourceConstraint = (new SourceConstraintBuilder())
            ->withPassport()
            ->build();

        $constraints = (new ConstraintsBuilder())
            ->withSourceConstraint($sourceConstraint)
            ->build();

        $wantedAttribute = (new WantedAttributeBuilder())
            ->withName($someName)
            ->withConstraints($constraints)
            ->build();

        $expectedJsonData = [
            'name' => $someName,
            'optional' => false,
            'constraints' => [
                [
                    'type' => "SOURCE",
                    "preferred_sources" => [
                        "anchors" => [
                            [
                                "name" => "PASSPORT",
                                "sub_type" => "",
                            ]
                        ],
                        "soft_preference" => false,
                    ]
                ]
            ]
        ];

        $this->assertEquals(json_encode($expectedJsonData), json_encode($wantedAttribute));
        $this->assertEquals(json_encode($expectedJsonData), $wantedAttribute);
    }
}
