<?php

declare(strict_types=1);

namespace Yoti\Sandbox\Test\Profile\Request;

use Yoti\Http\Payload;
use Yoti\Sandbox\Profile\Request\Attribute\SandboxAttribute;
use Yoti\Sandbox\Profile\Request\TokenRequest;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Sandbox\Profile\Request\TokenRequest
 */
class TokenRequestTest extends TestCase
{
    private const SOME_REMEMBER_ME_ID = 'some_remember_me_id';
    private const SOME_ATTRIBUTE_JSON_DATA = [
        'name' => 'some-name',
        'value' => 'some-value',
        'derivation' => '',
        'optional' => false,
        'anchors' => [],
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
        $someAttribute = $this->createMock(SandboxAttribute::class);
        $someAttribute
            ->method('jsonSerialize')
            ->willReturn(self::SOME_ATTRIBUTE_JSON_DATA);

        $this->tokenRequest = new TokenRequest(
            self::SOME_REMEMBER_ME_ID,
            [ $someAttribute ]
        );
    }

    /**
     * @covers ::jsonSerialize
     * @covers ::__construct
     */
    public function testJsonSerialize()
    {
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'remember_me_id' => self::SOME_REMEMBER_ME_ID,
                'profile_attributes' => [ self::SOME_ATTRIBUTE_JSON_DATA ],
            ]),
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
            (string) Payload::fromJsonData([
                'remember_me_id' => self::SOME_REMEMBER_ME_ID,
                'profile_attributes' => [ self::SOME_ATTRIBUTE_JSON_DATA ],
            ]),
            (string) $this->tokenRequest->getPayload()
        );
    }
}
