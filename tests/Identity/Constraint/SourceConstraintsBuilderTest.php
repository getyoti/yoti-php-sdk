<?php

namespace Yoti\Test\Identity\Constraint;

use Yoti\Identity\Constraint\PreferredSources;
use Yoti\Identity\Constraint\SourceConstraint;
use Yoti\Identity\Constraint\SourceConstraintBuilder;
use Yoti\Identity\Policy\WantedAnchor;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Identity\Constraint\SourceConstraintBuilder
 */
class SourceConstraintsBuilderTest extends TestCase
{
    /**
     * @covers ::build
     * @covers ::withSoftPreference
     * @covers ::withWantedAnchor
     * @covers \Yoti\Identity\Constraint\SourceConstraint::getPreferredSources
     * @covers \Yoti\Identity\Constraint\SourceConstraint::getType
     * @covers \Yoti\Identity\Constraint\SourceConstraint::__construct
     * @covers \Yoti\Identity\Constraint\Constraint::getType
     */
    public function testShouldBuildCorrectlyWithSingleAnchor()
    {
        $sourceConstraint = (new SourceConstraintBuilder())
            ->withWantedAnchor(new WantedAnchor('SOME_VALUE'))
            ->withSoftPreference(true)
            ->build();

        $this->assertInstanceOf(SourceConstraint::class, $sourceConstraint);
        $this->assertInstanceOf(PreferredSources::class, $sourceConstraint->getPreferredSources());
        $this->assertEquals('SOURCE', $sourceConstraint->getType());
    }

    /**
     * @covers ::build
     * @covers ::withWantedAnchors
     * @covers \Yoti\Identity\Constraint\SourceConstraint::__construct
     * @covers \Yoti\Identity\Constraint\SourceConstraint::jsonSerialize
     */
    public function testShouldBuildCorrectlyWithMultipleAnchors()
    {
        $wantedAnchors = [
            new WantedAnchor('some'),
            new WantedAnchor('some_2'),
        ];

        $sourceConstraint = (new SourceConstraintBuilder())
            ->withWantedAnchors($wantedAnchors)
            ->build();

        $expectedConstraint = [
            'type' => 'SOURCE',
            'preferred_sources' => $sourceConstraint->getPreferredSources()
        ];

        $this->assertEquals($wantedAnchors, $sourceConstraint->getPreferredSources()->getWantedAnchors());
        $this->assertEquals(
            json_encode($wantedAnchors),
            json_encode($sourceConstraint->getPreferredSources()->getWantedAnchors())
        );
        $this->assertEquals(json_encode($expectedConstraint), json_encode($sourceConstraint));
    }
}
