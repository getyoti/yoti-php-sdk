<?php

namespace YotiTest\ShareUrl;

use Yoti\ShareUrl\DynamicScenarioBuilder;
use Yoti\ShareUrl\Extension\ExtensionBuilder;
use Yoti\ShareUrl\Policy\DynamicPolicyBuilder;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\ShareUrl\DynamicScenarioBuilder
 */
class DynamicScenarioBuilderTest extends TestCase
{
    /**
     * @covers ::build
     * @covers ::withCallbackEndpoint
     * @covers ::withPolicy
     * @covers ::withExtension
     */
    public function testBuild()
    {
        $someCallback = '/test-callback';

        $somePolicy = (new DynamicPolicyBuilder())
            ->withFullName()
            ->withGivenNames()
            ->build();

        $someExtension1 = (new ExtensionBuilder())
            ->withType('some-extension-1')
            ->withContent(['some' => 'content 1'])
            ->build();

        $someExtension2 = (new ExtensionBuilder())
            ->withType('some-extension-2')
            ->withContent(['some' => 'content 2'])
            ->build();

        $dynamicScenario = (new DynamicScenarioBuilder())
            ->withCallbackEndpoint($someCallback)
            ->withPolicy($somePolicy)
            ->withExtension($someExtension1)
            ->withExtension($someExtension2)
            ->build();

        $expectedJsonData = [
            'callback_endpoint' => '/test-callback',
            'policy' => [
                'wanted' => [
                    [
                        'name' => 'full_name',
                        'derivation' => '',
                        'optional' => false,
                    ],
                    [
                        'name' => 'given_names',
                        'derivation' => '',
                        'optional' => false,
                    ],
                ],
                'wanted_auth_types' => [],
                'wanted_remember_me' => false,
                'wanted_remember_me_optional' => false,
            ],
            'extensions' => [
                [
                    'type' => 'some-extension-1',
                    'content' => [
                        'some' => 'content 1',
                    ],
                ],
                [
                    'type' => 'some-extension-2',
                    'content' => [
                        'some' => 'content 2',
                    ],
                ],
            ],
        ];

        $this->assertEquals(json_encode($expectedJsonData), json_encode($dynamicScenario));
        $this->assertEquals(json_encode($expectedJsonData), $dynamicScenario);
    }
}
