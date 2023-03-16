<?php

namespace Yoti\Test\Identity;

use Yoti\Identity\ShareSessionNotification;
use Yoti\Identity\ShareSessionNotificationBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Identity\ShareSessionNotificationBuilder
 */
class ShareSessionNotificationBuilderTest extends TestCase
{
    private const URL = 'some_url';

    private const HEADER_KEY = 'key_1';
    private const HEADER_VALUE = 'value_1';

    private const HEADERS = ['header_2' => 'auth', 'header_3' => 'auth_3'];

    /**
     * @covers ::withUrl
     * @covers ::withMethod
     * @covers ::withHeader
     * @covers ::withVerifyTls
     * @covers ::build
     * @covers \Yoti\Identity\ShareSessionNotification::getUrl
     * @covers \Yoti\Identity\ShareSessionNotification::getHeaders
     * @covers \Yoti\Identity\ShareSessionNotification::getMethod
     * @covers \Yoti\Identity\ShareSessionNotification::getUrl
     * @covers \Yoti\Identity\ShareSessionNotification::__construct
     */
    public function testShouldBuildCorrectly()
    {
        $shareNotification = (new ShareSessionNotificationBuilder())
            ->withMethod()
            ->withUrl(self::URL)
            ->withHeader(self::HEADER_KEY, self::HEADER_VALUE)
            ->withVerifyTls()
            ->build();

        $this->assertInstanceOf(ShareSessionNotification::class, $shareNotification);

        $this->assertEquals(self::URL, $shareNotification->getUrl());
        $this->assertEquals([self::HEADER_KEY =>  self::HEADER_VALUE], $shareNotification->getHeaders());
        $this->assertEquals('POST', $shareNotification->getMethod());
    }

    /**
     * @covers ::withUrl
     * @covers ::withMethod
     * @covers ::withHeaders
     * @covers ::withVerifyTls
     * @covers ::build
     * @covers \Yoti\Identity\ShareSessionNotification::getHeaders
     * @covers \Yoti\Identity\ShareSessionNotification::isVerifyTls
     * @covers \Yoti\Identity\ShareSessionNotification::jsonSerialize
     * @covers \Yoti\Identity\ShareSessionNotification::__construct
     */
    public function testShouldBuildCorrectlyWithMultipleHeaders()
    {
        $shareNotification = (new ShareSessionNotificationBuilder())
            ->withMethod()
            ->withUrl(self::URL)
            ->withHeaders(self::HEADERS)
            ->withVerifyTls(false)
            ->build();

        $expected = [
            'url' => self::URL,
            'method' => 'POST',
            'verifyTls' => false,
            'headers' => self::HEADERS,
        ];

        $this->assertEquals(self::HEADERS, $shareNotification->getHeaders());
        $this->assertFalse($shareNotification->isVerifyTls());
        $this->assertEquals(json_encode($expected), json_encode($shareNotification));
    }
}
