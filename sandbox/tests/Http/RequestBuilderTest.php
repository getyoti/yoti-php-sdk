<?php

namespace SandboxTest\Http;

use YotiTest\TestCase;
use YotiSandbox\Http\RequestBuilder;
use YotiSandbox\Http\TokenRequest;

class RequestBuilderTest extends TestCase
{
    /**
     * @var RequestBuilder
     */
    public $requestBuilder;

    /**
     * @var TokenRequest
     */
    public $tokenRequest;

    public function setUp()
    {
        $requestBuilder = new RequestBuilder();
        $requestBuilder->setRememberMeId('fake_remember_me_id');
        $requestBuilder->setFamilyName('Fake FamilyName');
        $this->requestBuilder = $requestBuilder;
    }

    public function testGetRequest()
    {
        $tokenRequest = $this->requestBuilder->createRequest();
        $this->assertInstanceOf(TokenRequest::class, $tokenRequest);
    }

    public function testGetRememberMeId()
    {
        $tokenRequest = $this->requestBuilder->createRequest();
        $this->assertEquals('fake_remember_me_id', $tokenRequest->getRememberMeId());
    }

    public function testShouldReturnFamilyNameAttr()
    {
        $tokenRequest = $this->requestBuilder->createRequest();
        $sandboxAttributes = $tokenRequest->getSandboxAttributes();
        $this->assertEquals($sandboxAttributes[0]['name'], 'family_name');
    }
}
