<?php

namespace SandboxTest;

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
        $sandboxPathManager = new SandboxPathManager();

        $this->sandboxClient = $this->getMockBuilder('YotiSandbox\SandboxClient')
            ->setConstructorArgs([SDK_ID, $this->pem, $sandboxPathManager])
            ->setMethods(['sendRequest'])
            ->getMock();
    }

    public function testGetToken()
    {
        $expectedToken = 'fake_token_xxx';
        $result['response'] = json_encode([
            'token' => $expectedToken
        ]);
        $result['http_code'] = 201;

        // Stub the method sendRequest to return the result we want
        $this->sandboxClient->method('sendRequest')
            ->willReturn($result);
        $token = $this->sandboxClient->getToken(new RequestBuilder(), 'POST');
        $this->assertEquals($expectedToken, $token);
    }
}
