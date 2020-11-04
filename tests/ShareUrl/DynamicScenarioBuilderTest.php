<?php

declare(strict_types=1);

namespace Yoti\Test\ShareUrl;

use Yoti\ShareUrl\DynamicScenario;
use Yoti\ShareUrl\DynamicScenarioBuilder;
use Yoti\ShareUrl\Extension\ExtensionBuilder;
use Yoti\ShareUrl\Policy\DynamicPolicy;
use Yoti\ShareUrl\Policy\DynamicPolicyBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\ShareUrl\DynamicScenarioBuilder
 */
class DynamicScenarioBuilderTest extends TestCase
{
    private const SOME_ENDPOINT = '/test-callback';

    /**
     * @covers ::build
     * @covers ::withCallbackEndpoint
     * @covers ::withPolicy
     * @covers ::withExtension
     * @covers \Yoti\ShareUrl\DynamicScenario::__construct
     * @covers \Yoti\ShareUrl\DynamicScenario::__toString
     * @covers \Yoti\ShareUrl\DynamicScenario::jsonSerialize
     */
    public function testBuild()
    {
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
            ->withCallbackEndpoint(self::SOME_ENDPOINT)
            ->withPolicy($somePolicy)
            ->withExtension($someExtension1)
            ->withExtension($someExtension2)
            ->build();

        $expectedJsonData = [
            'callback_endpoint' => self::SOME_ENDPOINT,
            'policy' => [
                'wanted' => [
                    [
                        'name' => 'full_name',
                        'optional' => false,
                    ],
                    [
                        'name' => 'given_names',
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

    /**
     * @covers ::build
     * @covers \Yoti\ShareUrl\DynamicScenario::__construct
     */
    public function testBuildWithoutCallback()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage(sprintf('%s::__construct()', DynamicScenario::class));

        (new DynamicScenarioBuilder())
            ->withPolicy($this->createMock(DynamicPolicy::class))
            ->build();
    }
}
