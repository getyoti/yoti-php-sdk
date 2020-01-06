<?php

namespace YotiTest;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Yoti\YotiClient;
use Yoti\Entity\Country;
use Yoti\Entity\AmlAddress;
use Yoti\Entity\AmlProfile;
use Yoti\Service\Aml\AmlResult;
use Yoti\Service\Profile\ActivityDetails;
use Yoti\Service\ShareUrl\ShareUrlResult;
use Yoti\ShareUrl\DynamicScenarioBuilder;
use Yoti\ShareUrl\Policy\DynamicPolicyBuilder;
use Yoti\Util\Config;

use function GuzzleHttp\Psr7\stream_for;

/**
 * @coversDefaultClass \Yoti\YotiClient
 */
class YotiClientTest extends TestCase
{
    /**
     * Test empty SDK ID
     *
     * @covers ::__construct
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage SDK ID cannot be empty
     */
    public function testEmptySdkId()
    {
        new YotiClient('', PEM_FILE);
    }

    /**
     * @covers ::getActivityDetails
     * @covers ::__construct
     */
    public function testGetActivityDetails()
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(file_get_contents(RECEIPT_JSON));
        $response->method('getStatusCode')->willReturn(200);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->willReturn($response);

        $yotiClient = new YotiClient(SDK_ID, PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
        ]);

        $this->assertInstanceOf(
            ActivityDetails::class,
            $yotiClient->getActivityDetails(YOTI_CONNECT_TOKEN)
        );
    }

    /**
     * @covers ::performAmlCheck
     * @covers ::__construct
     */
    public function testPerformAmlCheck()
    {
        $amlAddress = new AmlAddress(new Country('GBR'));
        $amlProfile = new AmlProfile('Edward Richard George', 'Heath', $amlAddress);

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(stream_for(file_get_contents(AML_CHECK_RESULT_JSON)));
        $response->method('getStatusCode')->willReturn(200);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(1))
            ->method('sendRequest')
            ->willReturn($response);

        $yotiClient = new YotiClient(SDK_ID, PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
        ]);

        $result = $yotiClient->performAmlCheck($amlProfile);

        $this->assertInstanceOf(AmlResult::class, $result);
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
        $response->method('getBody')->willReturn(stream_for(json_encode([
            'qrcode' => 'http://dynamic-code.yoti.com/some-qr-code',
            'ref_id' => 'some-ref-id',
        ])));
        $response->method('getStatusCode')->willReturn(201);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->willReturn($response);

        $yotiClient = new YotiClient(SDK_ID, PEM_FILE, [
            Config::HTTP_CLIENT => $httpClient,
        ]);

        $shareUrlResult = $yotiClient->createShareUrl($dynamicScenario);
        $this->assertInstanceOf(ShareUrlResult::class, $shareUrlResult);
    }

    public function testGetLoginUrl()
    {
        $someAppId = 'some-app-id';

        $this->assertEquals("https://www.yoti.com/connect/{$someAppId}", YotiClient::getLoginUrl($someAppId));
    }
}
