<?php

declare(strict_types=1);

namespace Yoti\Test\ShareUrl\Extension;

use Yoti\ShareUrl\Extension\ThirdPartyAttributeExtensionBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\ShareUrl\Extension\ThirdPartyAttributeExtensionBuilder
 */
class ThirdPartyAttributeExtensionBuilderTest extends TestCase
{
    private const THIRD_PARTY_ATTRIBUTE_TYPE = 'THIRD_PARTY_ATTRIBUTE';
    private const SOME_DEFINITION = 'some definition';
    private const SOME_OTHER_DEFINITION = 'some other definition';
    private const SOME_DATE_STRING = '2019-12-02T12:00:00.123Z';

    /**
     * @var \DateTime
     */
    private $someDate;

    public function setup(): void
    {
        $this->someDate = new \DateTime(self::SOME_DATE_STRING);
    }

    /**
     * @covers ::withExpiryDate
     * @covers ::withDefinition
     * @covers ::build
     */
    public function testBuild()
    {
        $thirdPartyAttributeExtension = (new ThirdPartyAttributeExtensionBuilder())
            ->withExpiryDate($this->someDate)
            ->withDefinition(self::SOME_DEFINITION)
            ->withDefinition(self::SOME_OTHER_DEFINITION)
            ->build();

        $expectedJson = $this->createExpectedJson(
            $this->someDate->format(\DateTime::RFC3339_EXTENDED),
            [
                self::SOME_DEFINITION,
                self::SOME_OTHER_DEFINITION,
            ]
        );

        $this->assertJsonStringEqualsJsonString(
            $expectedJson,
            json_encode($thirdPartyAttributeExtension)
        );
    }

    /**
     * @covers ::withDefinitions
     */
    public function testWithDefinitionsOverwritesExistingDefinitions()
    {
        $thirdPartyAttributeExtension = (new ThirdPartyAttributeExtensionBuilder())
            ->withExpiryDate($this->someDate)
            ->withDefinition('initial definition')
            ->withDefinitions([
                self::SOME_DEFINITION,
                self::SOME_OTHER_DEFINITION,
            ])
            ->build();

        $this->assertJsonStringEqualsJsonString(
            $this->createExpectedJson(
                $this->someDate->format(\DateTime::RFC3339_EXTENDED),
                [
                    self::SOME_DEFINITION,
                    self::SOME_OTHER_DEFINITION,
                ]
            ),
            json_encode($thirdPartyAttributeExtension)
        );
    }

    /**
     * @covers ::withExpiryDate
     *
     * @dataProvider expiryDateDataProvider
     */
    public function testWithExpiryDateFormat($inputDate, $outputDate)
    {
        $thirdPartyAttributeExtension = (new ThirdPartyAttributeExtensionBuilder())
            ->withExpiryDate(new \DateTime($inputDate))
            ->build();

        $this->assertJsonStringEqualsJsonString(
            $this->createExpectedJson($outputDate, []),
            json_encode($thirdPartyAttributeExtension)
        );
    }

    /**
     * Provides test expiry dates.
     */
    public function expiryDateDataProvider()
    {
        return [
            ['2020-01-02T01:02:03.123456Z', '2020-01-02T01:02:03.123+00:00'],
            ['2020-01-01T01:02:03.123+04:00', '2019-12-31T21:02:03.123+00:00'],
            ['2020-01-02T01:02:03.123-02:00', '2020-01-02T03:02:03.123+00:00']
        ];
    }

    /**
     * Create expected third party extension JSON.
     *
     * @param string $expiryDate
     * @param string[] $definitions
     *
     * @return string
     */
    private function createExpectedJson($expiryDate, $definitions)
    {
        return json_encode([
            'type' => self::THIRD_PARTY_ATTRIBUTE_TYPE,
            'content' => [
                'expiry_date' => $expiryDate,
                'definitions' => array_map(
                    function ($definition) {
                        return [ 'name' => $definition ];
                    },
                    $definitions
                ),
            ],
        ]);
    }
}
