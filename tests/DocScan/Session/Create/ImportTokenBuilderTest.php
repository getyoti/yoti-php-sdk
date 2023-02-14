<?php

namespace Yoti\Test\DocScan\Session\Create;

use Yoti\DocScan\Exception\DocScanException;
use Yoti\DocScan\Session\Create\ImportTokenBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\ImportTokenBuilder
 */
class ImportTokenBuilderTest extends TestCase
{
    private const DEFAULT_TTL = 3600 * 24 * 365;
    private const MIN_TTL = 3600 * 24 * 30;
    private const MAX_TTL = 3600 * 24 * 365;


    /**
     * @test
     * @covers ::build
     * @covers ::withTtl
     * @covers \Yoti\DocScan\Session\Create\ImportToken::__construct
     * @covers \Yoti\DocScan\Session\Create\ImportToken::validate
     * @covers \Yoti\DocScan\Session\Create\ImportToken::getTtl
     */
    public function builderWithDefaultTtlShouldSetCorrectTtl()
    {
        $result = (new ImportTokenBuilder())
            ->withTtl()
            ->build();

        $this->assertEquals(self::DEFAULT_TTL, $result->getTtl());
        $this->assertIsInt($result->getTtl());
    }

    /**
     * @test
     * @covers ::build
     * @covers ::withTtl
     * @covers \Yoti\DocScan\Session\Create\ImportToken::__construct
     * @covers \Yoti\DocScan\Session\Create\ImportToken::getTtl
     */
    public function builderWithTtlShouldSetCorrectTtl()
    {
        $ttl = 3600 * 24 * 60;

        $result = (new ImportTokenBuilder())
            ->withTtl($ttl)
            ->build();

        $this->assertEquals($ttl, $result->getTtl());
    }

    /**
     * @test
     * @covers \Yoti\DocScan\Session\Create\ImportToken::jsonSerialize
     */
    public function shouldSerializeWithCorrectProperties(): void
    {
        $ttl = 3600 * 24 * 60;

        $result = (new ImportTokenBuilder())
            ->withTtl($ttl)
            ->build();

        $expected = [
            'ttl' => $ttl,
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }

    /**
     * @test
     * @covers ::build
     * @covers ::withTtl
     * @covers \Yoti\DocScan\Session\Create\ImportToken::__construct
     * @covers \Yoti\DocScan\Session\Create\ImportToken::validate
     * @covers \Yoti\DocScan\Session\Create\ImportToken::getTtl
     */
    public function builderWithIncorrectSmallTtlShouldThrowException()
    {
        $this->expectException(DocScanException::class);
        $this->expectExceptionMessage(
            'Your TTL is invalid. Min value - ' . self::MIN_TTL . '.Max value - ' . self::MAX_TTL . '.'
        );

        $ttl = 3600 * 24 * 29;

        $result = (new ImportTokenBuilder())
            ->withTtl($ttl)
            ->build();
    }

    /**
     * @test
     * @covers ::build
     * @covers ::withTtl
     * @covers \Yoti\DocScan\Session\Create\ImportToken::__construct
     * @covers \Yoti\DocScan\Session\Create\ImportToken::validate
     * @covers \Yoti\DocScan\Session\Create\ImportToken::getTtl
     */
    public function builderWithIncorrectBigTtlShouldThrowException()
    {
        $this->expectException(DocScanException::class);
        $this->expectExceptionMessage(
            'Your TTL is invalid. Min value - ' . self::MIN_TTL . '.Max value - ' . self::MAX_TTL . '.'
        );

        $ttl = 3600 * 24 * 370;

        $result = (new ImportTokenBuilder())
            ->withTtl($ttl)
            ->build();
    }
}
