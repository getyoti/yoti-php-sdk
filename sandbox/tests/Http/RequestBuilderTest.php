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
        $tokenRequest = $this->requestBuilder->getRequest();
        $this->assertInstanceOf(TokenRequest::class, $tokenRequest);
    }

    public function testGetRememberMeId()
    {
        $tokenRequest = $this->requestBuilder->getRequest();
        $this->assertEquals('fake_remember_me_id', $tokenRequest->getRememberMeId());
    }

    public function testShouldReturnFamilyNameAttr()
    {
        $tokenRequest = $this->requestBuilder->getRequest();
        $sandboxAttributes = $tokenRequest->getSandboxAttributes();
        $attrs = $sandboxAttributes['profile_attributes'];
        $this->assertEquals($attrs[0]['name'], 'family_name');
    }
}