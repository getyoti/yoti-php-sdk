<?php

declare(strict_types=1);

namespace Yoti\Test;

use GuzzleHttp\Psr7;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LoggerInterface;
use Yoti\Exception\DateTimeException;
use Yoti\Profile\ActivityDetails;
use Yoti\ShareUrl\DynamicScenarioBuilder;
use Yoti\ShareUrl\Policy\DynamicPolicyBuilder;
use Yoti\ShareUrl\Result as ShareUrlResult;
use Yoti\Util\Config;
use Yoti\YotiClient;

/**
 * @coversDefaultClass \Yoti\YotiClient
 */
class YotiClientTest extends TestCase
{
    /**
     * Test empty SDK ID
     *
     * @covers ::__construct
     */
    public function testEmptySdkId()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('SDK ID cannot be empty');

        new YotiClient('', TestData::PEM_FILE);
    }

    /**
     * @covers ::getActivityDetails
     * @covers ::__construct
     */
    public function testGetActivityDetails()
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn(file_get_contents(TestData::RECEIPT_JSON));
        $stream->method('__toString')->willReturn(file_get_contents(TestData::RECEIPT_JSON));

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);
        $response->method('getStatusCode')->willReturn(200);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->willReturn($response);

        $yotiClient = new YotiClient(TestData::SDK_ID, TestData::PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
        ]);

        $this->assertInstanceOf(
            ActivityDetails::class,
            $yotiClient->getActivityDetails(file_get_contents(TestData::YOTI_CONNECT_TOKEN))
        );
    }

    /**
     * @covers ::createShareUrl
     * @covers ::__construct
     */
    public function testCreateShareUrl()
    {
        $dynamicScenario = (new DynamicScenarioBuilder())
            ->withCallbackEndpoint('/test-callback-url')
            ->withPolicy(
                (new DynamicPolicyBuilder())->build()
            )
            ->build();

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(Psr7\Utils::streamFor(json_encode([
            'qrcode' => 'http://dynamic-code.yoti.com/some-qr-code',
            'ref_id' => 'some-ref-id',
        ])));
        $response->method('getStatusCode')->willReturn(201);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn($response);

        $yotiClient = new YotiClient(TestData::SDK_ID, TestData::PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
        ]);

        $result = $yotiClient->createShareUrl($dynamicScenario);

        $this->assertInstanceOf(ShareUrlResult::class, $result);
    }


    /**
     * Asserts that the AML check method has been removed from the public API.
     *
     * @covers ::__construct
     */
    public function testPerformAmlCheckMethodDoesNotExist()
    {
        $this->assertFalse(
            method_exists(YotiClient::class, 'performAmlCheck'),
            'performAmlCheck() must not exist on YotiClient — the AML check endpoint has been decommissioned'
        );
    }

    /**
     * @covers ::getLoginUrl
     */
    public function testGetLoginUrl()
    {
        $someAppId = 'some-app-id';

        $this->assertEquals("https://www.yoti.com/connect/{$someAppId}", YotiClient::getLoginUrl($someAppId));
    }

    /**
     * @covers ::__construct
     */
    public function testCustomLogger()
    {
        $jsonstr = json_encode([
            'receipt' => [
                'timestamp'  => 'some invalid timestamp',
                'wrapped_receipt_key' => 'some receipt key',
                'sharing_outcome' => 'SUCCESS',
            ]
        ]);

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($jsonstr);
        $stream->method('__toString')->willReturn($jsonstr);

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn($stream);
        $response->method('getStatusCode')->willReturn(200);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->willReturn($response);

        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects($this->exactly(1))
            ->method('warning')
            ->with(
                'Could not parse string to DateTime',
                $this->callback(function ($context) {
                    $this->assertInstanceOf(DateTimeException::class, $context['exception']);
                    return true;
                })
            );

        $yotiClient = new YotiClient(TestData::SDK_ID, TestData::PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
            Config::LOGGER => $logger,
        ]);

        $yotiClient->getActivityDetails(file_get_contents(TestData::YOTI_CONNECT_TOKEN));
    }
}
