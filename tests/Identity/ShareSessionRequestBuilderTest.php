<?php

namespace Yoti\Test\Identity;

use Yoti\Identity\Extension\Extension;
use Yoti\Identity\Policy\Policy;
use Yoti\Identity\ShareSessionNotificationBuilder;
use Yoti\Identity\ShareSessionRequest;
use Yoti\Identity\ShareSessionRequestBuilder;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Identity\ShareSessionRequestBuilder
 */
class ShareSessionRequestBuilderTest extends TestCase
{
    private const URI = 'uri';

    private Extension $extensionMock;
    private Policy $policyMock;

    public function setup(): void
    {
        $this->extensionMock = $this->createMock(Extension::class);
        $this->policyMock = $this->createMock(Policy::class);
    }

    /**
     * @covers ::withRedirectUri
     * @covers ::withPolicy
     * @covers ::withExtension
     * @covers ::withNotification
     * @covers ::withSubject
     * @covers ::build
     * @covers \Yoti\Identity\ShareSessionRequest::getPolicy
     * @covers \Yoti\Identity\ShareSessionRequest::getNotification
     * @covers \Yoti\Identity\ShareSessionRequest::getExtensions
     * @covers \Yoti\Identity\ShareSessionRequest::getSubject
     * @covers \Yoti\Identity\ShareSessionRequest::__construct
     */
    public function testShouldBuildCorrectly()
    {
        $subject = [
            'key' => (object)['some' => 'good']
        ];

        $shareNotification = (new ShareSessionNotificationBuilder())
            ->withMethod()
            ->withUrl('some')
            ->withHeader('some', 'some')
            ->withVerifyTls()
            ->build();

        $shareRequest = (new ShareSessionRequestBuilder())
            ->withSubject($subject)
            ->withNotification($shareNotification)
            ->withPolicy($this->policyMock)
            ->withRedirectUri(self::URI)
            ->withExtension($this->extensionMock)
            ->build();

        $this->assertInstanceOf(ShareSessionRequest::class, $shareRequest);

        $this->assertEquals($subject, $shareRequest->getSubject());
        $this->assertEquals([$this->extensionMock], $shareRequest->getExtensions());
        $this->assertEquals($this->policyMock, $shareRequest->getPolicy());
        $this->assertEquals($shareNotification, $shareRequest->getNotification());
        $this->assertEquals(self::URI, $shareRequest->getRedirectUri());
    }

    /**
     * @covers ::withRedirectUri
     * @covers ::withPolicy
     * @covers ::withExtensions
     * @covers ::withNotification
     * @covers ::withSubject
     * @covers ::build
     * @covers \Yoti\Identity\ShareSessionRequest::getExtensions
     * @covers \Yoti\Identity\ShareSessionRequest::__construct
     * @covers \Yoti\Identity\ShareSessionRequest::jsonSerialize
     */
    public function testShouldBuildCorrectlyWithMultipleExtensions()
    {
        $shareRequest = (new ShareSessionRequestBuilder())
            ->withPolicy($this->policyMock)
            ->withRedirectUri(self::URI)
            ->withExtensions([$this->extensionMock])
            ->build();


        $expected = [
            'policy' => $this->policyMock,
            'redirectUri' => self::URI,
            'extensions' => [$this->extensionMock],
        ];

        $this->assertEquals([$this->extensionMock], $shareRequest->getExtensions());
        $this->assertEquals(json_encode($expected), json_encode($shareRequest));
    }
}
