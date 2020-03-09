<?php

declare(strict_types=1);

namespace Yoti\Sandbox\Test\Profile\Request\Attribute;

use Yoti\Sandbox\Profile\Request\Attribute\SandboxAttribute;
use Yoti\Test\TestCase;

/**
 * @coversDefaultClass \Yoti\Sandbox\Profile\Request\Attribute\SandboxAttribute
 */
class SandboxAttributeTest extends TestCase
{
    private const SOME_NAME = 'some-name';
    private const SOME_VALUE = 'some-value';

    /**
     * @var SandboxAttribute
     */
    public $attribute;

    public function setup(): void
    {
        $this->attribute = new SandboxAttribute(
            self::SOME_NAME,
            self::SOME_VALUE,
            ''
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
                'name' => self::SOME_NAME,
                'value' => self::SOME_VALUE,
                'derivation' => '',
                'optional' => false,
                'anchors' => [],
            ]),
            json_encode($this->attribute)
        );
    }
}
