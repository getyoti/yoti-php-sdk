<?php

namespace YotiTest\Service\ShareUrl;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Yoti\Constants;
use Yoti\ShareUrl\Service;
use Yoti\ShareUrl\DynamicScenario;
use Yoti\ShareUrl\DynamicScenarioBuilder;
use Yoti\ShareUrl\Policy\DynamicPolicyBuilder;
use Yoti\Util\Config;
use Yoti\Util\PemFile;
use YotiTest\TestCase;

use function GuzzleHttp\Psr7\stream_for;

/**
 * @coversDefaultClass \Yoti\ShareUrl\Service
 */
class ServiceTest extends TestCase
{
    /**
     * @covers ::createShareUrl
     * @covers ::__construct
     */
    public function testCreateShareUrl()
    {
        $expectedUrl = Constants::CONNECT_API_URL . sprintf('/qrcodes/apps/%s', SDK_ID) . '?appId=' . SDK_ID;
        $expectedUrlPattern = sprintf('~%s.*?nonce=.*?&timestamp=.*?~', preg_quote($expectedUrl));
        $expectedQrCode = 'https://dynamic-code.yoti.com/CAEaJDRjNTQ3M2IxLTNiNzktNDg3My1iMmM4LThiMTQxZDYwMjM5ODAC';
        $expectedRefId = '4c5473b1-3b79-4873-b2c8-8b141d602398';

        $dynamicScenario = (new DynamicScenarioBuilder())
            ->withCallbackEndpoint('/test-callback-url')
            ->withPolicy(
                (new DynamicPolicyBuilder())->build()
            )
            ->build();

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(stream_for(json_encode([
            'qrcode' => $expectedQrCode,
            'ref_id' => $expectedRefId,
        ])));
        $response->method('getStatusCode')->willReturn(201);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function ($request) use ($expectedUrlPattern, $dynamicScenario) {
                $this->assertRegExp($expectedUrlPattern, (string) $request->getUri());
                $this->assertEquals(json_encode($dynamicScenario), (string) $request->getBody());
                return true;
            }))
            ->willReturn($response);

        $service = new Service(new Config([
            Config::HTTP_CLIENT => $httpClient,
        ]));

        $result = $service->createShareUrl(
            $dynamicScenario,
            PemFile::fromFilePath(PEM_FILE),
            SDK_ID
        );

        $this->assertEquals($expectedQrCode, $result->getShareUrl());
        $this->assertEquals($expectedRefId, $result->getRefId());
    }

    /**
     * @covers ::createShareUrl
     *
     * @dataProvider httpErrorStatusCodeProvider
     *
     * @expectedException \Yoti\Exception\ShareUrlException
     */
    public function testCreateShareUrlFailure($statusCode)
    {
        $this->expectExceptionMessage("Server responded with {$statusCode}");
        $yotiClient = $this->createServiceWithErrorResponse($statusCode);
        $yotiClient->createShareUrl(
            $this->createMock(DynamicScenario::class),
            PemFile::fromFilePath(PEM_FILE),
            SDK_ID
        );
    }

    /**
     * @param int $statusCode
     *
     * @return \Yoti\YotiClient
     */
    private function createServiceWithErrorResponse($statusCode)
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')->willReturn(stream_for('{}'));
        $response->method('getStatusCode')->willReturn($statusCode);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient
            ->method('sendRequest')
            ->willReturn($response);

        return new Service(new Config([
            Config::HTTP_CLIENT => $httpClient,
        ]));
    }
}
