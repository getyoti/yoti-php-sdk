<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Create\Filters;

use Yoti\DocScan\Session\Create\Filters\RequiredShareCode;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Filters\RequiredShareCode
 */
class RequiredShareCodeTest extends TestCase
{
    private const SOME_ISSUER = 'someIssuer';
    private const SOME_SCHEME = 'someScheme';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getIssuer
     * @covers ::getScheme
     */
    public function shouldConstructWithIssuerAndScheme()
    {
        $requiredShareCode = new RequiredShareCode(self::SOME_ISSUER, self::SOME_SCHEME);

        $this->assertEquals(self::SOME_ISSUER, $requiredShareCode->getIssuer());
        $this->assertEquals(self::SOME_SCHEME, $requiredShareCode->getScheme());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getIssuer
     * @covers ::getScheme
     */
    public function shouldConstructWithNullValues()
    {
        $requiredShareCode = new RequiredShareCode();

        $this->assertNull($requiredShareCode->getIssuer());
        $this->assertNull($requiredShareCode->getScheme());
    }

    /**
     * @test
     * @covers ::jsonSerialize
     */
    public function shouldSerializeToJsonWithBothFields()
    {
        $requiredShareCode = new RequiredShareCode(self::SOME_ISSUER, self::SOME_SCHEME);

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'issuer' => self::SOME_ISSUER,
                'scheme' => self::SOME_SCHEME,
            ]),
            json_encode($requiredShareCode)
        );
    }

    /**
     * @test
     * @covers ::jsonSerialize
     */
    public function shouldSerializeToJsonWithOnlyIssuer()
    {
        $requiredShareCode = new RequiredShareCode(self::SOME_ISSUER, null);

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'issuer' => self::SOME_ISSUER,
            ]),
            json_encode($requiredShareCode)
        );
    }

    /**
     * @test
     * @covers ::jsonSerialize
     */
    public function shouldSerializeToJsonWithOnlyScheme()
    {
        $requiredShareCode = new RequiredShareCode(null, self::SOME_SCHEME);

        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'scheme' => self::SOME_SCHEME,
            ]),
            json_encode($requiredShareCode)
        );
    }

    /**
     * @test
     * @covers ::jsonSerialize
     */
    public function shouldSerializeToEmptyObjectWhenBothFieldsAreNull()
    {
        $requiredShareCode = new RequiredShareCode(null, null);

        $this->assertJsonStringEqualsJsonString(
            json_encode(new \stdClass()),
            json_encode($requiredShareCode)
        );
    }
}
