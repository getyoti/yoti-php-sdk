<?php

namespace SandboxTest;

use Yoti\Http\Response;
use YotiSandbox\Http\RequestBuilder;
use YotiSandbox\Http\SandboxPathManager;
use YotiTest\TestCase;

class SandboxClientTest extends TestCase
{
    public $pem;
    /**
     * @var \YotiSandbox\SandboxClient
     */
    public $sandboxClient;

    public function setUp()
    {
        $this->pem = file_get_contents(PEM_FILE);
        $sandboxPathManager = new SandboxPathManager(
            '/some-token-api-path',
            '/some-profile-api-path'
        );

        $this->sandboxClient = $this->getMockBuilder('YotiSandbox\SandboxClient')
            ->setConstructorArgs([SDK_ID, $this->pem, $sandboxPathManager])
            ->setMethods(['sendRequest'])
            ->getMock();
    }

    public function testGetToken()
    {
        $expectedToken = 'fake_token_xxx';
        $response = $this->createMock(Response::class);
        $response
            ->method('getBody')
            ->willReturn(json_encode([
                'token' => $expectedToken
            ]));
        $response
            ->method('getStatusCode')
            ->willReturn(201);

        // Stub the method sendRequest to return the result we want
        $this->sandboxClient->method('sendRequest')
            ->willReturn($response);
        $token = $this->sandboxClient->getToken(new RequestBuilder(), 'POST');
        $this->assertEquals($expectedToken, $token);
    }
}
