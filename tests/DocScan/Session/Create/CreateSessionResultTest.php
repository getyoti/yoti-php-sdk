<?php

namespace Yoti\Test\DocScan\Session\Create;

use Yoti\DocScan\Session\Create\CreateSessionResult;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Create\CreateSessionResult
 */
class CreateSessionResultTest extends TestCase
{

    private const SOME_CLIENT_SESSION_TOKEN_TTL = 30;
    private const SOME_CLIENT_SESSION_TOKEN = 'someClientSessionToken';
    private const SOME_SESSION_ID = 'someSessionId';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getClientSessionTokenTtl
     * @covers ::getClientSessionToken
     * @covers ::getSessionId
     */
    public function shouldExtractValuesFromArray()
    {
        $input = [
            'client_session_token_ttl' => self::SOME_CLIENT_SESSION_TOKEN_TTL,
            'client_session_token' => self::SOME_CLIENT_SESSION_TOKEN,
            'session_id' => self::SOME_SESSION_ID,
        ];

        $result = new CreateSessionResult($input);

        $this->assertEquals(self::SOME_CLIENT_SESSION_TOKEN_TTL, $result->getClientSessionTokenTtl());
        $this->assertEquals(self::SOME_CLIENT_SESSION_TOKEN, $result->getClientSessionToken());
        $this->assertEquals(self::SOME_SESSION_ID, $result->getSessionId());
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getClientSessionTokenTtl
     * @covers ::getClientSessionToken
     * @covers ::getSessionId
     */
    public function shouldNotThrowExceptionWhenValuesAreNull()
    {
        $input = [];

        $result = new CreateSessionResult($input);

        $this->assertNull($result->getClientSessionTokenTtl());
        $this->assertNull($result->getClientSessionToken());
        $this->assertNull($result->getSessionId());
    }
}
