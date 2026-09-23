<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Create\Filters;

use Yoti\DocScan\Session\Create\Filters\Provider;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Filters\Provider
 */
class ProviderTest extends TestCase
{
    private const SOME_PROVIDER_NAME = 'DIGILOCKER';

    /**
     * @test
     *
     * @covers ::__construct
     * @covers ::getName
     * @covers ::jsonSerialize
     */
    public function shouldSerializeWithName()
    {
        $provider = new Provider(self::SOME_PROVIDER_NAME);

        $this->assertEquals(self::SOME_PROVIDER_NAME, $provider->getName());

        $this->assertJsonStringEqualsJsonString(
            json_encode((object) [
                'name' => self::SOME_PROVIDER_NAME,
            ]),
            json_encode($provider)
        );
    }

    /**
     * @test
     *
     * @covers ::__construct
     */
    public function shouldThrowExceptionWhenNameIsEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('name cannot be empty');

        new Provider('');
    }
}
