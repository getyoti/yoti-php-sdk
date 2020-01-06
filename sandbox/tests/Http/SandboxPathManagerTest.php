<?php

namespace SandboxTest\Http;

use YotiSandbox\Http\SandboxPathManager;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \YotiSandbox\Http\SandboxPathManager
 */
class SandboxPathManagerTest extends TestCase
{
    const SOME_TOKEN_PATH = 'some-token-path';

    /**
     * @var \YotiSandbox\Http\SandboxPathManager
     */
    private $sandboxPathManager;

    /**
     * Setup SandboxPathManager
     */
    public function setup()
    {
        $this->sandboxPathManager = new SandboxPathManager(
            self::SOME_TOKEN_PATH
        );
    }

    /**
     * @covers ::getTokenApiPath
     * @covers ::__construct
     */
    public function testGetTokenApiPath()
    {
        $this->assertEquals(
            self::SOME_TOKEN_PATH,
            $this->sandboxPathManager->getTokenApiPath()
        );
    }
}
