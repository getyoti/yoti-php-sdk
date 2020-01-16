<?php

declare(strict_types=1);

namespace YotiTest\Util;

use Psr\Http\Client\ClientInterface;
use Yoti\Constants;
use Yoti\Util\Config;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\Util\Config
 */
class ConfigTest extends TestCase
{
    const SDK_IDENTIFIER_KEY = 'sdk.identifier';
    const SDK_VERSION_KEY = 'sdk.version';
    const CONNECT_API_URL_KEY = 'connect.api.url';
    const HTTP_CLIENT_KEY = 'http.client';

    const SOME_SDK_IDENTIFIER = 'some identifier';
    const SOME_SDK_VERSION = 'some version';
    const SOME_CONNECT_API_URL = 'http://example.com/api';

    /**
     * @covers ::getSdkIdentifier
     * @covers ::__construct
     * @covers ::validateKeys
     * @covers ::get
     * @covers ::set
     * @covers ::setStringValue
     */
    public function testGetSdkIdentifier()
    {
        $config = new Config([
            self::SDK_IDENTIFIER_KEY => self::SOME_SDK_IDENTIFIER
        ]);

        $this->assertEquals(self::SOME_SDK_IDENTIFIER, $config->getSdkIdentifier());
    }

    /**
     * @covers ::getSdkIdentifier
     */
    public function testGetSdkIdentifierDefault()
    {
        $this->assertEquals(Constants::SDK_IDENTIFIER, (new Config())->getSdkIdentifier());
    }

    /**
     * @covers ::getSdkVersion
     * @covers ::__construct
     * @covers ::validateKeys
     * @covers ::get
     * @covers ::set
     * @covers ::setStringValue
     */
    public function testGetSdkVersion()
    {
        $config = new Config([
            self::SDK_VERSION_KEY => self::SOME_SDK_VERSION
        ]);

        $this->assertEquals(self::SOME_SDK_VERSION, $config->getSdkVersion());
    }

    /**
     * @covers ::getSdkVersion
     */
    public function testGetSdkVersionDefault()
    {
        $this->assertEquals(Constants::SDK_VERSION, (new Config())->getSdkVersion());
    }

    /**
     * @covers ::getConnectApiUrl
     * @covers ::__construct
     * @covers ::validateKeys
     * @covers ::get
     * @covers ::set
     * @covers ::setStringValue
     */
    public function testGetConnectApiUrl()
    {
        $config = new Config([
            self::CONNECT_API_URL_KEY => self::SOME_CONNECT_API_URL
        ]);

        $this->assertEquals(self::SOME_CONNECT_API_URL, $config->getConnectApiUrl());
    }

    /**
     * @covers ::getConnectApiUrl
     */
    public function testGetConnectApiUrlDefault()
    {
        $this->assertEquals(Constants::CONNECT_API_URL, (new Config())->getConnectApiUrl());
    }

    /**
     * @covers ::getHttpClient
     * @covers ::__construct
     * @covers ::validateKeys
     * @covers ::get
     * @covers ::set
     * @covers ::setHttpClient
     */
    public function testGetHttpClient()
    {
        $someHttpClient = $this->createMock(ClientInterface::class);
        $config = new Config([
            self::HTTP_CLIENT_KEY => $someHttpClient,
        ]);

        $this->assertSame($someHttpClient, $config->getHttpClient());
    }


    /**
     * @covers ::setHttpClient
     */
    public function testInvalidHttpClient()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'http.client configuration value must be of type %s',
            \Psr\Http\Client\ClientInterface::class
        ));

        new Config([
            self::HTTP_CLIENT_KEY => 'some invalid http client',
        ]);
    }

    /**
     * @covers ::getHttpClient
     */
    public function testGetHttpClientDefault()
    {
        $this->assertNull((new Config())->getHttpClient());
    }

    /**
     * @covers ::__construct
     * @covers ::validateKeys
     */
    public function testValidateKeys()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The following configuration keys are not allowed: some.invalid.key');

        new Config([
            'some.invalid.key' => 'some string',
        ]);
    }
}
