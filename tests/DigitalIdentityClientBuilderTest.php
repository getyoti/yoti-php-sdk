<?php

declare(strict_types=1);

namespace Yoti\Test;

use Yoti\DigitalIdentityClient;
use Yoti\DigitalIdentityClientBuilder;

/**
 * @coversDefaultClass \Yoti\DigitalIdentityClientBuilder
 */
class DigitalIdentityClientBuilderTest extends TestCase
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
        $client = DigitalIdentityClient::builder()
            ->withClientSdkId(self::SOME_SDK_ID)
            ->withPemFilePath(TestData::PEM_FILE)
            ->build();

        $this->assertInstanceOf(DigitalIdentityClient::class, $client);
    }

    /**
     * @test
     * @covers ::withAuthenticationToken
     * @covers ::build
     */
    public function shouldBuildWithAuthenticationToken()
    {
        $client = DigitalIdentityClient::builder()
            ->withAuthenticationToken(self::SOME_AUTH_TOKEN)
            ->build();

        $this->assertInstanceOf(DigitalIdentityClient::class, $client);
    }

    /**
     * @test
     * @covers ::build
     */
    public function shouldThrowWhenAuthTokenSetWithSdkId()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Must not supply sdkId or PEM file when using an authentication token');

        DigitalIdentityClient::builder()
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

        DigitalIdentityClient::builder()
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

        DigitalIdentityClient::builder()
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

        DigitalIdentityClient::builder()
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

        DigitalIdentityClient::builder()
            ->build();
    }

    /**
     * @test
     * @covers ::withAuthenticationToken
     * @covers ::build
     */
    public function shouldThrowOnEmptyAuthToken()
    {
        $this->expectException(\InvalidArgumentException::class);

        DigitalIdentityClient::builder()
            ->withAuthenticationToken('')
            ->build();
    }
}
