<?php

namespace Yoti\Test\DocScan\Session\Retrieve\Configuration\Capture\Task;

use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Task\RequestedIdDocTaskResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Task\RequestedSupplementaryDocTaskResponse;
use Yoti\DocScan\Session\Retrieve\Configuration\Capture\Task\UnknownRequestedTaskResponse;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\DocScan\Session\Retrieve\Configuration\Capture\Task\RequestedTaskResponse
 */
class RequestedTaskResponseTest extends TestCase
{
    private const SOME_TYPE = 'SOME_TYPE';
    private const SOME_STATE = 'SOME_STATE';

    /**
     * @test
     * @covers ::getType
     * @covers ::getState
     * @covers \RequestedIdDocTaskResponse::__construct
     * @covers \RequestedSupplementaryDocTaskResponse::__construct
     */
    public function shouldBuildCorrectly()
    {
        $input = [
            'type' => self::SOME_TYPE,
            'state' => self::SOME_STATE
        ];

        $requestedIdTaskResponse = new RequestedIdDocTaskResponse($input);
        $requestedSupplementaryDocTaskResponse = new RequestedSupplementaryDocTaskResponse($input);
        $unknownRequestedTaskResponse = new UnknownRequestedTaskResponse();

        $this->assertEquals(self::SOME_TYPE, $requestedIdTaskResponse->getType());
        $this->assertEquals(self::SOME_STATE, $requestedSupplementaryDocTaskResponse->getState());
        $this->assertInstanceOf(UnknownRequestedTaskResponse::class, $unknownRequestedTaskResponse);
    }
}
