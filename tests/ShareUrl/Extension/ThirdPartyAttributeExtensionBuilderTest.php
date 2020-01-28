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
    const THIRD_PARTY_ATTRIBUTE_TYPE = 'THIRD_PARTY_ATTRIBUTE';
    const SOME_DEFINITION = 'some definition';
    const SOME_OTHER_DEFINITION = 'some other definition';

    /**
     * @covers ::withExpiryDate
     * @covers ::withDefinition
     * @covers ::build
     */
    public function testBuild()
    {
        $thirdPartyAttributeExtension = (new ThirdPartyAttributeExtensionBuilder())
            ->withExpiryDate(new \DateTime('2019-12-02T12:00:00.123Z'))
            ->withDefinition(self::SOME_DEFINITION)
            ->withDefinition(self::SOME_OTHER_DEFINITION)
            ->build();

        $expectedJson = $this->createExpectedJson(
            '2019-12-02T12:00:00.123000+00:00',
            [
                self::SOME_DEFINITION,
                self::SOME_OTHER_DEFINITION,
            ]
        );

        $this->assertEquals($expectedJson, json_encode($thirdPartyAttributeExtension));
    }

    /**
     * @covers ::withDefinitions
     */
    public function testWithDefinitionsOverwritesExistingDefinitions()
    {
        $thirdPartyAttributeExtension = (new ThirdPartyAttributeExtensionBuilder())
            ->withExpiryDate(new \DateTime('2019-12-02T12:00:00.123Z'))
            ->withDefinition('initial definition')
            ->withDefinitions([
                self::SOME_DEFINITION,
                self::SOME_OTHER_DEFINITION,
            ])
            ->build();

        $this->assertEquals(
            $this->createExpectedJson(
                '2019-12-02T12:00:00.123000+00:00',
                [
                    self::SOME_DEFINITION,
                    self::SOME_OTHER_DEFINITION,
                ]
            ),
            json_encode($thirdPartyAttributeExtension)
        );
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
