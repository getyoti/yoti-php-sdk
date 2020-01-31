<?php

declare(strict_types=1);

namespace Yoti\Test\ShareUrl\Policy;

use Yoti\ShareUrl\Policy\SourceConstraintBuilder;
use Yoti\ShareUrl\Policy\WantedAnchorBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\ShareUrl\Policy\SourceConstraintBuilder
 */
class SourceConstraintBuilderTest extends TestCase
{
    private const ANCHOR_TYPE_SOURCE = 'SOURCE';
    private const ANCHOR_VALUE_PASSPORT = 'PASSPORT';
    private const ANCHOR_VALUE_DRIVING_LICENSE = 'DRIVING_LICENCE';
    private const ANCHOR_VALUE_NATIONAL_ID = 'NATIONAL_ID';
    private const ANCHOR_VALUE_PASSCARD = 'PASS_CARD';
    private const ANCHOR_SUB_TYPE_NATIONAL_ID = 'NATIONAL_ID_SUB_TYPE';
    private const ANCHOR_SUB_TYPE_PASSCARD = 'PASSCARD_SUB_TYPE';
    private const SOME_VALUE = 'test value';
    private const SOME_SUB_TYPE = 'test sub type';

    /**
     * @covers ::build
     * @covers \Yoti\ShareUrl\Policy\SourceConstraint::__construct
     * @covers \Yoti\ShareUrl\Policy\SourceConstraint::__toString
     * @covers \Yoti\ShareUrl\Policy\SourceConstraint::jsonSerialize
     */
    public function testBuild()
    {
        $sourceConstraint = (new SourceConstraintBuilder())
            ->build();

        $expectedJsonData = [
            'type' => self::ANCHOR_TYPE_SOURCE,
            'preferred_sources' => [
                'anchors' => [],
                'soft_preference' => false,
            ]
        ];

        $this->assertEquals(json_encode($expectedJsonData), json_encode($sourceConstraint));
        $this->assertEquals(json_encode($expectedJsonData), $sourceConstraint);
    }

    /**
     * @covers ::withPassport
     */
    public function testWithPassport()
    {
        $sourceConstraint = (new SourceConstraintBuilder())
            ->withPassport()
            ->build();

        $this->assertSourceConstraint($sourceConstraint, self::ANCHOR_VALUE_PASSPORT, '');
    }

    /**
     * @covers ::withPassport
     */
    public function testWithPassportSubType()
    {
        $sourceConstraint = (new SourceConstraintBuilder())
            ->withPassport(self::SOME_SUB_TYPE)
            ->build();

        $this->assertSourceConstraint($sourceConstraint, self::ANCHOR_VALUE_PASSPORT, self::SOME_SUB_TYPE);
    }

    /**
     * @covers ::withPasscard
     */
    public function testWithPasscard()
    {
        $sourceConstraint = (new SourceConstraintBuilder())
            ->withPasscard()
            ->build();

        $this->assertSourceConstraint($sourceConstraint, self::ANCHOR_VALUE_PASSCARD, '');
    }

    /**
     * @covers ::withPasscard
     */
    public function testWithPasscardSubType()
    {
        $sourceConstraint = (new SourceConstraintBuilder())
            ->withPasscard(self::SOME_SUB_TYPE)
            ->build();

        $this->assertSourceConstraint($sourceConstraint, self::ANCHOR_VALUE_PASSCARD, self::SOME_SUB_TYPE);
    }

    /**
     * @covers ::withDrivingLicence
     */
    public function testWithDrivingLicence()
    {
        $sourceConstraint = (new SourceConstraintBuilder())
            ->withDrivingLicence()
            ->build();

        $this->assertSourceConstraint($sourceConstraint, self::ANCHOR_VALUE_DRIVING_LICENSE, '');
    }

    /**
     * @covers ::withDrivingLicence
     */
    public function testWithDrivingLicenceSubType()
    {
        $sourceConstraint = (new SourceConstraintBuilder())
            ->withDrivingLicence(self::SOME_SUB_TYPE)
            ->build();

        $this->assertSourceConstraint($sourceConstraint, self::ANCHOR_VALUE_DRIVING_LICENSE, self::SOME_SUB_TYPE);
    }

    /**
     * @covers ::withNationalId
     */
    public function testWithNationalId()
    {
        $sourceConstraint = (new SourceConstraintBuilder())
            ->withNationalId()
            ->build();

        $this->assertSourceConstraint($sourceConstraint, self::ANCHOR_VALUE_NATIONAL_ID, '');
    }

    /**
     * @covers ::withNationalId
     */
    public function testWithNationalIdSubType()
    {
        $sourceConstraint = (new SourceConstraintBuilder())
            ->withNationalId(self::SOME_SUB_TYPE)
            ->build();

        $this->assertSourceConstraint($sourceConstraint, self::ANCHOR_VALUE_NATIONAL_ID, self::SOME_SUB_TYPE);
    }


    /**
     * @covers ::withAnchorByValue
     */
    public function testWithConstraintByValue()
    {
        $sourceConstraint = (new SourceConstraintBuilder())
            ->withAnchorByValue(self::SOME_VALUE)
            ->build();

        $this->assertSourceConstraint($sourceConstraint, self::SOME_VALUE, '');
    }

    /**
     * @covers ::withAnchorByValue
     */
    public function testWithConstraintByValueAndSubType()
    {
        $sourceConstraint = (new SourceConstraintBuilder())
            ->withAnchorByValue(self::SOME_VALUE, self::SOME_SUB_TYPE)
            ->build();

        $this->assertSourceConstraint($sourceConstraint, self::SOME_VALUE, self::SOME_SUB_TYPE);
    }

    /**
     * @covers ::withAnchor
     */
    public function testWithConstraintByWantedAnchor()
    {
        $someAnchor = (new WantedAnchorBuilder())
            ->withValue(self::SOME_VALUE)
            ->withSubType('')
            ->build();

        $sourceConstraint = (new SourceConstraintBuilder())
            ->withAnchor($someAnchor)
            ->build();

        $this->assertSourceConstraint($sourceConstraint, self::SOME_VALUE, '');
    }

    /**
     * @covers ::withAnchor
     */
    public function testWithConstraintByWantedAnchorWithDefaultSubType()
    {
        $someAnchor = (new WantedAnchorBuilder())
            ->withValue(self::SOME_VALUE)
            ->build();

        $sourceConstraint = (new SourceConstraintBuilder())
            ->withAnchor($someAnchor)
            ->build();

        $this->assertSourceConstraint($sourceConstraint, self::SOME_VALUE, '');
    }

    /**
     * @covers ::withAnchor
     */
    public function testWithConstraintByWantedAnchorAndSubType()
    {
        $someAnchor = (new WantedAnchorBuilder())
            ->withValue(self::SOME_VALUE)
            ->withSubType(self::SOME_SUB_TYPE)
            ->build();

        $sourceConstraint = (new SourceConstraintBuilder())
            ->withAnchor($someAnchor)
            ->build();

        $this->assertSourceConstraint($sourceConstraint, self::SOME_VALUE, self::SOME_SUB_TYPE);
    }

    /**
     * @covers ::withDrivingLicence
     * @covers ::withNationalId
     * @covers ::withPasscard
     */
    public function testMultipleAnchors()
    {
        $sourceConstraint = (new SourceConstraintBuilder())
            ->withDrivingLicence()
            ->withNationalId(self::ANCHOR_SUB_TYPE_NATIONAL_ID)
            ->withPasscard(self::ANCHOR_SUB_TYPE_PASSCARD)
            ->build();

        $expectedJsonData = [
            'type' => self::ANCHOR_TYPE_SOURCE,
            'preferred_sources' => [
                'anchors' => [
                    [
                        'name' => self::ANCHOR_VALUE_DRIVING_LICENSE,
                        'sub_type' => '',
                    ],
                    [
                        'name' => self::ANCHOR_VALUE_NATIONAL_ID,
                        'sub_type' => self::ANCHOR_SUB_TYPE_NATIONAL_ID,
                    ],
                    [
                        'name' => self::ANCHOR_VALUE_PASSCARD,
                        'sub_type' => self::ANCHOR_SUB_TYPE_PASSCARD,
                    ],
                ],
                'soft_preference' => false,
            ]
        ];

        $this->assertEquals(json_encode($expectedJsonData), json_encode($sourceConstraint));
        $this->assertEquals(json_encode($expectedJsonData), $sourceConstraint);
    }

    /**
     * @covers ::withSoftPreference
     */
    public function testConstraintWithSoftPreference()
    {
        $sourceConstraint = (new SourceConstraintBuilder())
            ->withDrivingLicence()
            ->withSoftPreference()
            ->build();

        $this->assertSourceConstraint($sourceConstraint, self::ANCHOR_VALUE_DRIVING_LICENSE, '', true);
    }

    /**
     * @covers ::withSoftPreference
     */
    public function testConstraintWithoutSoftPreference()
    {
        $sourceConstraint = (new SourceConstraintBuilder())
            ->withDrivingLicence()
            ->withSoftPreference(false)
            ->build();

        $this->assertSourceConstraint($sourceConstraint, self::ANCHOR_VALUE_DRIVING_LICENSE, '', false);
    }

    /**
     * @covers ::withSoftPreference
     */
    public function testConstraintWithSoftPreferenceSetOnce()
    {
        $sourceConstraint = (new SourceConstraintBuilder())
            ->withDrivingLicence()
            ->withSoftPreference(false)
            ->withSoftPreference(true)
            ->build();

        $this->assertSourceConstraint($sourceConstraint, self::ANCHOR_VALUE_DRIVING_LICENSE, '', true);
    }

    /**
     * Assert provided source constraint serializes to correct JSON.
     *
     * @param \Yoti\ShareUrl\Policy\SourceConstraint $sourceConstraint
     * @param string $expectValue
     * @param string $expectSubType
     * @param bool $expectSoftPreference
     */
    private function assertSourceConstraint(
        $sourceConstraint,
        $expectValue,
        $expectSubType,
        $expectSoftPreference = false
    ) {
        $expectedJsonData = [
            'type' => self::ANCHOR_TYPE_SOURCE,
            'preferred_sources' => [
                'anchors' => [
                    [
                        'name' => $expectValue,
                        'sub_type' => $expectSubType,
                    ],
                ],
                'soft_preference' => $expectSoftPreference,
            ]
        ];

        $this->assertEquals(json_encode($expectedJsonData), json_encode($sourceConstraint));
        $this->assertEquals(json_encode($expectedJsonData), $sourceConstraint);
    }
}
