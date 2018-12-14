<?php

namespace SandboxTest\Http;

use YotiTest\TestCase;
use Yoti\Entity\Profile;
use YotiSandbox\Http\TokenRequest;

class TokenRequestTest extends TestCase
{
    /**
     * @var TokenRequest
     */
    public $tokenRequest;
    /**
     * @var array
     */
    public $sandboxAttrs;

    public function setUp()
    {
        $dummyAttrs = [
            'name' => Profile::ATTR_FAMILY_NAME,
            'value' => 'fake_family_name',
            'derivation' => '',
            'optional' => 'false',
            'anchors' => []
        ];
        $this->sandboxAttrs = [
            $dummyAttrs
        ];
        $this->tokenRequest = new TokenRequest('fake_remember_me_id', $this->sandboxAttrs);
    }

    public function testGetSandboxAttributes()
    {
        $expectedAttrs= $this->sandboxAttrs;
        $this->assertEquals(
            json_encode($expectedAttrs),
            json_encode($this->tokenRequest->getSandboxAttributes())
        );
    }
}