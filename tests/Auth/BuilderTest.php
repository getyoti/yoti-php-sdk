<?php

declare(strict_types=1);

namespace Yoti\Test\Auth;

use Yoti\Auth\Builder;
use Yoti\Auth\AuthenticationTokenGenerator;
use Yoti\Auth\Properties;
use Yoti\Test\TestCase;
use Yoti\Test\TestData;
use Yoti\Util\PemFile;

/**
 * @coversDefaultClass \Yoti\Auth\Builder
 */
class BuilderTest extends TestCase
{
    private const SOME_SDK_ID = 'some-sdk-id';
    private const SOME_AUTH_URL = 'https://custom-auth.api.yoti.com/v1/oauth/token';

    /**
     * @test
     * @covers ::withSdkId
     * @covers ::withPemFile
     * @covers ::build
     */
    public function shouldBuildTokenGeneratorWithRequiredFields()
    {
        $pemFile = PemFile::fromFilePath(TestData::PEM_FILE);

        $generator = (new Builder())
            ->withSdkId(self::SOME_SDK_ID)
            ->withPemFile($pemFile)
            ->build();

        $this->assertInstanceOf(AuthenticationTokenGenerator::class, $generator);
    }

    /**
     * @test
     * @covers ::withSdkId
     * @covers ::withPemFilePath
     * @covers ::build
     */
    public function shouldBuildWithPemFilePath()
    {
        $generator = (new Builder())
            ->withSdkId(self::SOME_SDK_ID)
            ->withPemFilePath(TestData::PEM_FILE)
            ->build();

        $this->assertInstanceOf(AuthenticationTokenGenerator::class, $generator);
    }

    /**
     * @test
     * @covers ::build
     */
    public function shouldThrowWhenSdkIdIsEmpty()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("'sdkId' must not be empty or null");

        $pemFile = PemFile::fromFilePath(TestData::PEM_FILE);

        (new Builder())
            ->withSdkId('')
            ->withPemFile($pemFile)
            ->build();
    }

    /**
     * @test
     * @covers ::build
     */
    public function shouldThrowWhenSdkIdIsMissing()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("'sdkId' must not be empty or null");

        $pemFile = PemFile::fromFilePath(TestData::PEM_FILE);

        (new Builder())
            ->withPemFile($pemFile)
            ->build();
    }

    /**
     * @test
     * @covers ::build
     */
    public function shouldThrowWhenPemFileIsMissing()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("'pemFile' must not be null");

        (new Builder())
            ->withSdkId(self::SOME_SDK_ID)
            ->build();
    }

    /**
     * @test
     * @covers ::withJwtIdSupplier
     * @covers ::build
     */
    public function shouldAcceptCustomJwtIdSupplier()
    {
        $pemFile = PemFile::fromFilePath(TestData::PEM_FILE);

        $generator = (new Builder())
            ->withSdkId(self::SOME_SDK_ID)
            ->withPemFile($pemFile)
            ->withJwtIdSupplier(function () {
                return 'custom-jwt-id';
            })
            ->build();

        $this->assertInstanceOf(AuthenticationTokenGenerator::class, $generator);
    }

    /**
     * @test
     * @covers ::withAuthApiUrl
     * @covers ::build
     */
    public function shouldAcceptCustomAuthApiUrl()
    {
        $pemFile = PemFile::fromFilePath(TestData::PEM_FILE);

        $generator = (new Builder())
            ->withSdkId(self::SOME_SDK_ID)
            ->withPemFile($pemFile)
            ->withAuthApiUrl(self::SOME_AUTH_URL)
            ->build();

        $this->assertInstanceOf(AuthenticationTokenGenerator::class, $generator);
    }
}
