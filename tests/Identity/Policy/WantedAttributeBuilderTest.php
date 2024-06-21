<?php

declare(strict_types=1);

namespace Yoti\Test\Identity\Policy;

use Yoti\Identity\Constraint\SourceConstraintBuilder;
use Yoti\Identity\Policy\WantedAnchor;
use Yoti\Identity\Policy\WantedAttributeBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Identity\Policy\WantedAttributeBuilder
 */
class WantedAttributeBuilderTest extends TestCase
{
    /**
     * @covers ::build
     * @covers ::withName
     * @covers ::withDerivation
     * @covers ::withConstraint
     * @covers ::withOptional
     * @covers \Yoti\Identity\Policy\WantedAttribute::__construct
     * @covers \Yoti\Identity\Policy\WantedAttribute::jsonSerialize
     * @covers \Yoti\Identity\Policy\WantedAttribute::getDerivation
     * @covers \Yoti\Identity\Policy\WantedAttribute::getName
     * @covers \Yoti\Identity\Policy\WantedAttribute::getConstraints
     * @covers \Yoti\Identity\Policy\WantedAttribute::getOptional
     * @covers \Yoti\Identity\Policy\WantedAttribute::getAcceptSelfAsserted
     */
    public function testBuild()
    {
        $someName = 'some name';
        $someDerivation = 'some derivation';

        $sourceConstraint = (new SourceConstraintBuilder())
            ->withWantedAnchor(new WantedAnchor('SOME'))
            ->build();

        $wantedAttribute = (new WantedAttributeBuilder())
            ->withName($someName)
            ->withDerivation($someDerivation)
            ->withOptional(true)
            ->withConstraint($sourceConstraint)
            ->withAcceptSelfAsserted(false)
            ->build();

        $expectedJsonData = [
            'name' => $someName,
            'optional' => true,
            'derivation' => $someDerivation,
            'constraints' => [$sourceConstraint],
            'accept_self_asserted' => false,
        ];

        $this->assertEquals(json_encode($expectedJsonData), json_encode($wantedAttribute));
        $this->assertTrue($wantedAttribute->getOptional());
        $this->assertContains($sourceConstraint, $wantedAttribute->getConstraints());
        $this->assertFalse($wantedAttribute->getAcceptSelfAsserted());
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
            ->withAcceptSelfAsserted(true)
            ->build();

        $this->assertEquals(json_encode($expectedJsonData), json_encode($wantedAttributeDefault));

        $wantedAttribute = (new WantedAttributeBuilder())
            ->withName($someName)
            ->withAcceptSelfAsserted(true)
            ->build();

        $this->assertEquals(json_encode($expectedJsonData), json_encode($wantedAttribute));
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
    }

    /**
     * @covers ::withAcceptSelfAsserted
     * @covers ::withConstraints
     * @covers \Yoti\ShareUrl\Policy\WantedAttribute::__construct
     * @covers \Yoti\ShareUrl\Policy\WantedAttribute::jsonSerialize
     * @covers \Yoti\ShareUrl\Policy\WantedAttribute::getAcceptSelfAsserted
     */
    public function testWithMultipleConstraints()
    {
        $sourceConstraint = (new SourceConstraintBuilder())
            ->withWantedAnchor(new WantedAnchor('SOME'))
            ->build();

        $sourceConstraint2 = (new SourceConstraintBuilder())
            ->withWantedAnchor(new WantedAnchor('SOME_2'))
            ->build();


        $constraints = [
            $sourceConstraint,
            $sourceConstraint2
        ];

        $wantedAttribute = (new WantedAttributeBuilder())
            ->withName('someName')
            ->withAcceptSelfAsserted(false)
            ->withConstraints($constraints)
            ->build();

        $this->assertEquals($constraints, $wantedAttribute->getConstraints());
    }
}
