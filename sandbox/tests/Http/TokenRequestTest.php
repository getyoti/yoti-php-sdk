<?php

namespace SandboxTest\Http;

use YotiTest\TestCase;
use Yoti\Entity\Profile;
use Yoti\Http\Payload;
use YotiSandbox\Http\TokenRequest;

class TokenRequestTest extends TestCase
{
    const SOME_REMEMBER_ME_ID = 'some_remember_me_id';
    const SOME_FAMILY_NAME = 'some family name';

    /**
     * @var TokenRequest
     */
    private $tokenRequest;

    /**
     * @var array
     */
    private $someSandboxAttributes;

    public function setUp()
    {
        $this->someSandboxAttributes = [
            [
                'name' => Profile::ATTR_FAMILY_NAME,
                'value' => 'fake_family_name',
                'derivation' => '',
                'optional' => 'false',
                'anchors' => []
            ]
        ];
        $this->tokenRequest = new TokenRequest(self::SOME_REMEMBER_ME_ID, $this->someSandboxAttributes);
    }

    public function testGetSandboxAttributes()
    {
        $this->assertEquals(
            json_encode($this->someSandboxAttributes),
            json_encode($this->tokenRequest->getSandboxAttributes())
        );
    }

    public function testGetPayload()
    {
        $this->assertEquals(
            new Payload([
                'remember_me_id' => self::SOME_REMEMBER_ME_ID,
                'profile_attributes' => $this->someSandboxAttributes,
            ]),
            $this->tokenRequest->getPayload()
        );
    }
}
