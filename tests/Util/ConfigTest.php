<?php

declare(strict_types=1);

namespace Yoti\Test\Util;

use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;
use Yoti\Constants;
use Yoti\Test\TestCase;
use Yoti\Util\Config;

/**
 * @coversDefaultClass \Yoti\Util\Config
 */
class ConfigTest extends TestCase
{
    private const SDK_IDENTIFIER_KEY = 'sdk.identifier';
    private const SDK_VERSION_KEY = 'sdk.version';
    private const API_URL_KEY = 'api.url';
    private const HTTP_CLIENT_KEY = 'http.client';
    private const LOGGER_KEY = 'logger';

    private const SOME_SDK_IDENTIFIER = 'some identifier';
    private const SOME_SDK_VERSION = 'some version';
    private const SOME_API_URL = 'http://example.com/api';

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
     * @covers ::getApiUrl
     * @covers ::__construct
     * @covers ::validateKeys
     * @covers ::get
     * @covers ::set
     * @covers ::setStringValue
     */
    public function testGetApiUrl()
    {
        $config = new Config([
            self::API_URL_KEY => self::SOME_API_URL
        ]);

        $this->assertEquals(self::SOME_API_URL, $config->getApiUrl());
    }

    /**
     * @covers ::getApiUrl
     */
    public function testGetApiUrlDefaultIsNull()
    {
        $this->assertNull((new Config())->getApiUrl());
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
            ClientInterface::class
        ));

        new Config([
            self::HTTP_CLIENT_KEY => 'some invalid http client',
        ]);
    }

    /**
     * @covers ::getHttpClient
     * @covers ::setHttpClient
     */
    public function testGetHttpClientDefault()
    {
        $this->assertInstanceOf(
            ClientInterface::class,
            (new Config())->getHttpClient()
        );
    }

    /**
     * @covers ::setLogger
     * @covers ::getLogger
     * @covers ::validateKeys
     * @covers ::__construct
     */
    public function testGetLogger()
    {
        $someLogger = $this->createMock(LoggerInterface::class);
        $config = new Config([
            self::LOGGER_KEY => $someLogger,
        ]);

        $this->assertSame($someLogger, $config->getLogger());
    }

    /**
     * @covers ::setLogger
     */
    public function testInvalidLogger()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'logger configuration value must be of type %s',
            LoggerInterface::class
        ));

        new Config([
            self::LOGGER_KEY => 'some invalid logger',
        ]);
    }

    /**
     * @covers ::setLogger
     * @covers ::getLogger
     */
    public function testGetLoggerDefault()
    {
        $this->assertInstanceOf(
            LoggerInterface::class,
            (new Config())->getLogger()
        );
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

    /**
     * @covers ::__construct
     */
    public function testNullValuesIgnored()
    {
        $config = new Config([
            Config::SDK_IDENTIFIER => null,
        ]);

        $this->assertEquals(Constants::SDK_IDENTIFIER, $config->getSdkIdentifier());
    }
}
