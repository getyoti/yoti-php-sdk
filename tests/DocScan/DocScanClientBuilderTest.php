<?php

declare(strict_types=1);

namespace Yoti\Test\DocScan;

use Yoti\DocScan\DocScanClient;
use Yoti\DocScan\DocScanClientBuilder;
use Yoti\Test\TestCase;
use Yoti\Test\TestData;
use Yoti\Util\Config;

/**
 * @coversDefaultClass \Yoti\DocScan\DocScanClientBuilder
 */
class DocScanClientBuilderTest extends TestCase
{
    private const SOME_AUTH_TOKEN = 'some-bearer-auth-token';
    private const SOME_SDK_ID = 'some-sdk-id';

    /**
     * @test
     * @covers ::withClientSdkId
     * @covers ::withPemFilePath
     * @covers ::build
     */
    public function shouldBuildWithSignedRequestAuth()
    {
        $client = DocScanClient::builder()
            ->withClientSdkId(self::SOME_SDK_ID)
            ->withPemFilePath(TestData::PEM_FILE)
            ->build();

        $this->assertInstanceOf(DocScanClient::class, $client);
    }

    /**
     * @test
     * @covers ::withAuthenticationToken
     * @covers ::build
     */
    public function shouldBuildWithAuthenticationToken()
    {
        $client = DocScanClient::builder()
            ->withAuthenticationToken(self::SOME_AUTH_TOKEN)
            ->build();

        $this->assertInstanceOf(DocScanClient::class, $client);
    }

    /**
     * @test
     * @covers ::build
     */
    public function shouldThrowWhenAuthTokenSetWithSdkId()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Must not supply sdkId or PEM file when using an authentication token');

        DocScanClient::builder()
            ->withAuthenticationToken(self::SOME_AUTH_TOKEN)
            ->withClientSdkId(self::SOME_SDK_ID)
            ->build();
    }

    /**
     * @test
     * @covers ::build
     */
    public function shouldThrowWhenAuthTokenSetWithPem()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Must not supply sdkId or PEM file when using an authentication token');

        DocScanClient::builder()
            ->withAuthenticationToken(self::SOME_AUTH_TOKEN)
            ->withPemFilePath(TestData::PEM_FILE)
            ->build();
    }

    /**
     * @test
     * @covers ::build
     */
    public function shouldThrowWhenNoSdkIdForSignedRequest()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('An sdkId and PEM file must be provided when not using an authentication token');

        DocScanClient::builder()
            ->withPemFilePath(TestData::PEM_FILE)
            ->build();
    }

    /**
     * @test
     * @covers ::build
     */
    public function shouldThrowWhenNoPemForSignedRequest()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('An sdkId and PEM file must be provided when not using an authentication token');

        DocScanClient::builder()
            ->withClientSdkId(self::SOME_SDK_ID)
            ->build();
    }

    /**
     * @test
     * @covers ::build
     */
    public function shouldThrowWhenNothingProvided()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('An sdkId and PEM file must be provided when not using an authentication token');

        DocScanClient::builder()
            ->build();
    }

    /**
     * @test
     * @covers ::withOptions
     * @covers ::build
     */
    public function shouldAcceptCustomOptions()
    {
        $client = DocScanClient::builder()
            ->withAuthenticationToken(self::SOME_AUTH_TOKEN)
            ->withOptions([Config::SDK_IDENTIFIER => 'CustomSDK'])
            ->build();

        $this->assertInstanceOf(DocScanClient::class, $client);
    }

    /**
     * @test
     * @covers ::withAuthenticationToken
     * @covers ::build
     */
    public function shouldThrowOnEmptyAuthToken()
    {
        $this->expectException(\InvalidArgumentException::class);

        DocScanClient::builder()
            ->withAuthenticationToken('')
            ->build();
    }
}
