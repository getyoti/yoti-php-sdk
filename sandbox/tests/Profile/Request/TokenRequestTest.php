<?php

declare(strict_types=1);

namespace Yoti\Sandbox\Test\Profile\Request;

use Yoti\Http\Payload;
use Yoti\Profile\UserProfile;
use Yoti\Sandbox\Profile\Request\Attribute\SandboxAnchor;
use Yoti\Sandbox\Profile\Request\Attribute\SandboxAttribute;
use Yoti\Sandbox\Profile\Request\TokenRequest;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Sandbox\Profile\Request\TokenRequest
 */
class TokenRequestTest extends TestCase
{
    private const SOME_REMEMBER_ME_ID = 'some_remember_me_id';
    private const SOME_VALUE = 'some-value';
    private const SOME_ANCHOR_JSON_DATA = [
        'type' => 'some type',
        'sub_type' => 'some sub type',
        'value' => 'some anchor value',
        'timestamp' => 1575998454,
    ];

    /**
     * @var TokenRequest
     */
    private $tokenRequest;

    /**
     * Setup TokenRequest
     */
    public function setup(): void
    {
        $someAnchor = $this->createMock(SandboxAnchor::class);
        $someAnchor->method('jsonSerialize')->willReturn(self::SOME_ANCHOR_JSON_DATA);

        $this->tokenRequest = new TokenRequest(
            self::SOME_REMEMBER_ME_ID,
            [
                new SandboxAttribute(
                    UserProfile::ATTR_FAMILY_NAME,
                    self::SOME_VALUE,
                    '',
                    false,
                    [ $someAnchor ]
                )
            ]
        );
    }

    /**
     * The expected JSON data.
     */
    private function expectedJsonData()
    {
        return [
            'remember_me_id' => self::SOME_REMEMBER_ME_ID,
            'profile_attributes' => [
                [
                    'name' => UserProfile::ATTR_FAMILY_NAME,
                    'value' => self::SOME_VALUE,
                    'derivation' => '',
                    'optional' => false,
                    'anchors' => [ self::SOME_ANCHOR_JSON_DATA ],
                ]
            ]
        ];
    }

    /**
     * @covers ::jsonSerialize
     * @covers ::__construct
     */
    public function testJsonSerialize()
    {
        $this->assertJsonStringEqualsJsonString(
            json_encode($this->expectedJsonData()),
            json_encode($this->tokenRequest)
        );
    }

    /**
     * @covers ::getPayload
     * @covers ::__construct
     */
    public function testGetPayload()
    {
        $this->assertEquals(
            (string) Payload::fromJsonData($this->expectedJsonData()),
            (string) $this->tokenRequest->getPayload()
        );
    }
}
