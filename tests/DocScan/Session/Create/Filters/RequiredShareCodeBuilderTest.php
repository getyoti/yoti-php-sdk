<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan\Session\Create\Filters;

use Yoti\DocScan\Session\Create\Filters\RequiredShareCodeBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\Filters\RequiredShareCodeBuilder
 */
class RequiredShareCodeBuilderTest extends TestCase
{
    private const SOME_ISSUER = 'someIssuer';
    private const SOME_SCHEME = 'someScheme';

    /**
     * @test
     * @covers ::withIssuer
     * @covers ::withScheme
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredShareCode::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredShareCode::getIssuer
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredShareCode::getScheme
     */
    public function shouldBuildWithIssuerAndScheme()
    {
        $requiredShareCode = (new RequiredShareCodeBuilder())
            ->withIssuer(self::SOME_ISSUER)
            ->withScheme(self::SOME_SCHEME)
            ->build();

        $this->assertEquals(self::SOME_ISSUER, $requiredShareCode->getIssuer());
        $this->assertEquals(self::SOME_SCHEME, $requiredShareCode->getScheme());
    }

    /**
     * @test
     * @covers ::withIssuer
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredShareCode::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredShareCode::getIssuer
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredShareCode::getScheme
     */
    public function shouldBuildWithOnlyIssuer()
    {
        $requiredShareCode = (new RequiredShareCodeBuilder())
            ->withIssuer(self::SOME_ISSUER)
            ->build();

        $this->assertEquals(self::SOME_ISSUER, $requiredShareCode->getIssuer());
        $this->assertNull($requiredShareCode->getScheme());
    }

    /**
     * @test
     * @covers ::withScheme
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredShareCode::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredShareCode::getIssuer
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredShareCode::getScheme
     */
    public function shouldBuildWithOnlyScheme()
    {
        $requiredShareCode = (new RequiredShareCodeBuilder())
            ->withScheme(self::SOME_SCHEME)
            ->build();

        $this->assertNull($requiredShareCode->getIssuer());
        $this->assertEquals(self::SOME_SCHEME, $requiredShareCode->getScheme());
    }

    /**
     * @test
     * @covers ::build
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredShareCode::__construct
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredShareCode::getIssuer
     * @covers \Yoti\DocScan\Session\Create\Filters\RequiredShareCode::getScheme
     */
    public function shouldBuildWithNoFields()
    {
        $requiredShareCode = (new RequiredShareCodeBuilder())->build();

        $this->assertNull($requiredShareCode->getIssuer());
        $this->assertNull($requiredShareCode->getScheme());
    }
}
